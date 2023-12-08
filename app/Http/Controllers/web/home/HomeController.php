<?php

namespace App\Http\Controllers\Web\Home;

use App\Helpers\ApiResponse;
use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\web\Home\CreateGetOffersRequest;
use App\Http\Requests\web\SearchRequest;
use App\Models\Area;
use App\Models\BannerModel;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\GetOffer;
use App\Models\Product;
use App\Settings\GeneralSettings;
use Doctrine\DBAL\Connection;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maize\Markable\Exceptions\InvalidMarkValueException;
use Maize\Markable\Models\Favorite;

class HomeController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function get_category_with_products()
    {
        $categories = Category::active()->withWhereHas('products.coverImage')->with('image')->whereNull('parent_id')->take(3)->get();
        $parentcategories = Category::active()->withWhereHas('image')->whereNull('parent_id')->get();
        $exclusiveProducts = Product::active()->with('coverImage')->where('is_exclusive', 1)->get();
        $banner = BannerModel::active()->withWhereHas('image')->inRandomOrder()->first();
        $brands = Brand::active()->with('image')->take(8)->get();

        return view('index', compact(['categories', 'exclusiveProducts', 'parentcategories', 'banner', 'brands']));
    }


    /**
     * @param $id
     * @param GeneralSettings $settings
     * @return Application|Factory|View
     */
    public function show($id, GeneralSettings $settings): View|Factory|Application
    {
        $product = Product::with(['brand', 'category', 'reviews', 'image', 'productGuideFile','coverImage'])->findOrFail($id);

        $related_products = Product::query()
            ->inRandomOrder()
            ->where('category_id', $product->getAttribute('category_id'))
            ->whereNot('id', $id)
            ->limit(5)
            ->get();


        $return_policy = $settings->returnPolicy;

        return view('products.show', compact('product', 'related_products', 'return_policy'));
    }

    /** Add and Remove Favourite
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidMarkValueException
     */
    public function toggle_favourite(Request $request)
    {
        $product = Product::find($request->product_id);
        if (Favorite::has($product, auth()->user())) {
            Favorite::remove($product, auth()->user());
            return ApiResponse::ResponseSuccess(data: [
                'create' => false,
            ],
                message: "Favourite deleted successfully");
        } else {
            $favourite = Favorite::add($product, auth()->user());
        }
        return ApiResponse::ResponseSuccess(data: [
            'favourite' => $favourite,
            'create' => true,
        ], message: "your favourite created successfully");
    }

    /**
     * @param CreateGetOffersRequest $request
     * @return RedirectResponse
     */
    public function create_get_offers(CreateGetOffersRequest $request)
    {
        GetOffer::create($request->validated());


        return redirect()->back();
    }

    /**
     * Search in Products.
     *
     * @param SearchRequest $request
     * @return View|Factory
     */
    public function search(SearchRequest $request): View|Factory
    {

        // First if we have category, we get it to get "it's" children.
        $mainCategory = null;
        if ($request->has('category_id')) {
            $mainCategory = Category::query()
                ->with(['children', 'parent'])
                ->active()
                ->find($request->input('category_id'));

        }
        // Building Our Product Query.
        $query = Product::query()
            ->when(filled($mainCategory),

            function($q)use ($mainCategory) {
                $childrenIds =$mainCategory ->children()->select('id')->pluck('id');
                $q->where('category_id', $mainCategory->getKey())
                    ->OrWhereIn('category_id',$childrenIds);
            })
            ->when(request('exclusive'), fn($q) => $q->where('is_exclusive',true))
            ->when(request('sort_by'), function ($q) {
        if (request('sort_by') == 'lowest_price') {

            $q->orderBy('final_price');
        } else if (request('sort_by') == 'highest_price') {

            $q->orderBy('final_price', 'DESC');
        }

    })
        ->when(request('brand_ids'), fn($q) => $q->whereIn('brand_id', request('brand_ids')))
        ->when(request('min') && request('max'), function ($q) {
            $q->whereBetween('price', [request('min'), request('max')]);
        })
        ->when(request('search'), function ($q, $search) {
            $keyword = '%' . Str::lower($search) . '%';
            return $q->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(`name`) LIKE ?', [$keyword]);
            });
        });

        $brandIds = $query->clone()
            ->select('brand_id')
//            ->groupBy('brand_id')
            ->pluck('brand_id');

        $brands = Brand::query()->active()->whereIn('id', $brandIds)->get();

        $maxPrice = $query->clone()
            ->select('price')
            ->orderBy('price', 'desc')
            ->first()
            ?->getAttribute('price') ?? null;

        $minPrice = $query->clone()
            ->select('price')
            ->orderBy('price', 'asc')
            ->first()
            ?->getAttribute('price') ?? null;

        $products = $query->clone()
            ->with(['coverImage'])
            ->withAvg('reviews', 'stars')
            ->withCount('reviews')
            ->paginate(5);


        return view('products.index')
            ->with([
                'products' => $products,
                'main_category' => $mainCategory,
                'brands' => $brands,
                'max_price' => $maxPrice,
                'min_price' => $minPrice,
            ]);
    }


    /**
     * @return Application|Factory|View
     */
    public function get_cities()
    {
        $cities = City::active()->get();

        return view('pages.forms', compact('cities'));
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function get_areas(Request $request)
    {
        $areas = Area::active()->where('city_id', $request->city_id)->get();
        return response()->json([
            'areas' => $areas
        ]);
    }
}

