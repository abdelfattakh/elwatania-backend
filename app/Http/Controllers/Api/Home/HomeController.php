<?php

namespace App\Http\Controllers\Api\Home;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\AddFavourite;
use App\Models\BannerModel;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Settings\GeneralSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maize\Markable\Models\Favorite;

/**
 * @group General
 * @subgroup Home
 */
class HomeController extends ApiController
{
    /**
     * Get Application Startup Data.
     *
     * @param GeneralSettings $settings
     * @return JsonResponse
     */
    public function data(GeneralSettings $settings): JsonResponse
    {
        $brands = Brand::active()
            ->with('image')
            ->get();

        $banners = BannerModel::active()
            ->with('image')
            ->get();

        $countries = Country::active()
            ->with(['cities.areas'])
            ->get();

        $paymentMethod = PaymentMethod::active()
            ->get();

        return ApiResponse::ResponseSuccess(data: [
            'brands' => $brands,
            'banners' => $banners,
            'countries' => $countries,
            'paymentMethod' => $paymentMethod,
            'settings' => $settings->toArray(),
        ]);
    }

    /**
     * Get Products.
     *
     * @queryParam search string
     * @queryParam category_id int
     * @queryParam is_exclusive bool
     * @param Request $request
     * @return JsonResponse
     */
    public function get_products(Request $request): JsonResponse
    {
        $products = Product::query()
            ->active()
            ->when($request->filled('search'), function ($q) use ($request) {
                $keyword = '%' . Str::lower($request->string('search')) . '%';

                return $q->where(function ($q) use ($keyword) {
                    $q->whereRaw('LOWER(`name`) LIKE ?', [$keyword]);
                });
            })
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->integer('category_id')))
            ->when($request->filled('is_exclusive'), fn($q) => $q->where('is_exclusive', $request->boolean('is_exclusive')))
            ->with(['category', 'image', 'coverImage'])
            ->withAvg('reviews', 'stars')
            ->withCount('reviews')
            ->simplePaginate();

        return ApiResponse::ResponseSuccess(data: [
            'products' => $products,
        ]);
    }

    /**
     * Get Categories with Children.
     *
     * @return JsonResponse
     */
    public function get_categories_with_children(): JsonResponse
    {
        $categories = Category::active()
            ->with('image')
            ->where('parent_id', null)
            ->with('children.image')
            ->simplePaginate();

        return ApiResponse::ResponseSuccess(data: [
            'categories' => $categories,
        ]);
    }

    /**
     * Get Single Product.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function get_product(int $id): JsonResponse
    {
        $product = Product::query()
            ->with(['category', 'image', 'coverImage'])
            ->withAvg('reviews', 'stars')
            ->withCount('reviews')
            ->findOrFail($id);

        return ApiResponse::ResponseSuccess(data: [
            'product' => $product,
        ]);
    }

    public function add_favourite(AddFavourite $request): JsonResponse
    {
        $product = Product::find($request->validated('product_id'));
        if (Favorite::has($product, $this->user)) {
            Favorite::remove($product, $this->user);
            return ApiResponse::ResponseSuccess(message: "Favourite deleted successfully");
        } else {
            $favourite = Favorite::add($product, $this->user);
        }
        return ApiResponse::ResponseSuccess(data: [
            'favourite' => $favourite,
        ], message: "your favourite created successfully");
    }

    /**
     * @return JsonResponse
     * get auth user favourite
     * @authenticated
     */
    public function get_user_favourites(): JsonResponse
    {
        $favourites = Favorite::with('markable')->where('user_id', $this->user->id)->get();

        return ApiResponse::ResponseSuccess(data: [
            'favourites' => $favourites,

        ]);
    }

    /**
     * @return JsonResponse
     * get auth user notifications
     * @authenticated
     */
    public function get_user_notifications(): JsonResponse
    {
        return ApiResponse::ResponseSuccess(data: [
            'notifications' => $this->user->notifications()->get()
        ]);
    }
}
