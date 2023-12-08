<?php

namespace App\Http\Controllers\Web\Home;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\web\Home\ContactUsRequest;
use App\Models\Address;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\ContactUs;
use App\Models\Coupon;
use App\Models\Product;
use App\Settings\GeneralSettings;
use Darryldecode\Cart\Cart;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maize\Markable\Models\Favorite;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IndexController extends Controller
{
    /**
     * render contactUs page
     * @return View
     */
    public function contact_us(GeneralSettings $settings): View
    {

        return view('pages.contactUs', ['phone' => $settings->phone, 'email' => $settings->email]);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function change_email()
    {
        return view('pages.emailChange');
    }

    public function changePass()
    {
        return view('pages.changePass');
    }

    /**
     * Get Address page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function address()
    {
        return view('pages.address.address');
    }

    /**
     * Get update Address form
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function update_address($id)
    {
        $address = Address::findOrFail($id);
        $cities = City::active()->with('areas')->get();
        $areas = Area::all();

        return view('pages.address.update_address', compact(['address', 'cities', 'areas']));
    }

    /**
     * Render Update Profile
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function update_profile()
    {
        return view('pages.profile');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Render Send Reset password page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function send_reset_code(): View
    {

        return view('pages.resetPassword');
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function profile(): View
    {
        $last_order = auth()->user()?->orders()->where('status', OrderStatusEnum::completed())->with('orderItems')->latest()->first();

        if (filled($last_order)) {
            $address = $last_order->address()->first();
            return view('pages.main', compact(['last_order', 'address']));
        }
        return view('pages.main', compact('last_order'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function forms(): View
    {
        $cities = City::all();
        $areas = Area::all();

        return view('pages.forms', compact(['cities', 'areas']));
    }

    /**
     * Render Orders page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function request(): View
    {
        $current_orders = auth()->user()->orders()->where('status', OrderStatusEnum::inProgress())->with('orderItems')->get();
        $previous_orders = auth()->user()->orders()->whereIn('status', [OrderStatusEnum::completed(), OrderStatusEnum::cancelled()])->with('orderItems')->get();
        $orders = auth()->user()->orders()->paginate(5);


        return view('pages.request', compact(['current_orders', 'previous_orders', 'orders']));
    }

    /**
     * send auth user favourites to perfect page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function perfect(): View
    {

        $products = Product::whereHasFavorite(
            auth()->user()
        )->paginate('6');

        return view('pages.perfect', compact('products'));
    }


    /**
     * render basket page
     * @param GeneralSettings $settings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function basket(GeneralSettings $settings): View
    {
        DB::enableQueryLog();
        $carts = \Cart::session(auth()->user()->id)->getContent();

        $productsIds = [];
        foreach ($carts as $item) {
            array_push($productsIds, $item->id);
        }


        $productCollection = Product::whereIn('id', $productsIds)->get();
        $filter = $productCollection->filter(function ($value, $key) {
            if ($value['is_available'] == 0) {
                return true;
            }
        });

        $product_ids = $filter->map(function ($item) {
            return $item->id;
        })->toArray();
        \Cart::session(auth()->user()->id)->remove($product_ids);

        $items = \Cart::session(auth()->user()->id)->getContent()->toArray();
        $cart = collect(array_values($items))->sortBy('id');
        $queries=DB::getQueryLog();
        dd($queries);
        return view('pages.basket', [
            'cart' => $cart,
            'sub_total' => \Cart::session(auth()->user()->id)->getSubTotal(),
            'tax' => $settings->tax_price . '%',
        ]);
    }

    /**
     * Render register page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function register(): View
    {
        return view('auth.register');
    }

    /**
     *
     * render checkout out page
     * @param $address_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     */
    public function pay($address_id): View|RedirectResponse
    {

        $address = Address::find($address_id);
        $items = \Cart::session(auth()->user()->id)->getContent()->toArray();
        $sub_total = \Cart::session(auth()->user()->id)->getSubTotal();
        $cart = array_values($items);

        $taxCondition = \Cart::session(auth()->user()->id)->getConditionsByType('tax');
        $couponCondition = \Cart::session(auth()->user()->id)->getConditionsByType('coupon');
        if (filled($couponCondition)) {
            $coupon_code = head(array_keys(head($couponCondition)));
            $coupon_id = Coupon::where('code', $coupon_code)->first()->id;
        }
        if (filled($taxCondition)) {
            $tax = head(head($taxCondition))->getValue();
        }
        $coupon_value = 0;
        foreach ($couponCondition as $condition) {
            $coupon_value += $condition->getCalculatedValue($sub_total);
        }

        $delivery_fees = $address->city->delivery_fees;
        $items = \Cart::session(auth()->user()->id)->getContent()->toArray();
        $cart = array_values($items);

        $condition1 = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'delivery',
            'type' => 'delivery',
            'target' => 'total',
            'value' => $delivery_fees,
        ));

        \Cart::condition($condition1);
        \Cart::session(auth()->user()->id)->condition($condition1);

        if (\Cart::session(auth()->user()->id)->isEmpty()) {

            return redirect()->route('basket');
        }
        return view('pages.pay-step', ([
            'address' => $address,
            'cart' => $cart,
            'total' => round(\Cart::session(auth()->user()->id)->getTotal(), 2),
            'sub_total' => \Cart::session(auth()->user()->id)->getSubTotal(),
            'tax' => $tax ?? 0,
            'coupon_value' => $coupon_value ?? 0,
            'couponCondition' => $couponCondition ?? 0,
            'coupon_id' => $coupon_id ?? 0
        ]));
    }

    /**
     * create contact us
     * @param ContactUsRequest $request
     * @return RedirectResponse
     */
    public function submit_contact_us(ContactUsRequest $request): RedirectResponse
    {
        ContactUs::create($request->validated());

        return redirect()->route('home');
    }

    /**
     * @return RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function send_verification_email(): RedirectResponse
    {
        event(new Registered(session()->get('user')));
        session()->flash('message', __('web.send_verification_email'));
        return redirect()->back();
    }

    /**
     * Render Verify ResetPassword
     * @return View
     */
    public function verify_reset_code(): View
    {
        return view('pages.changeResetPassword');

    }

    /**
     * Render Verify verify ResetOtp
     * @return View
     */
    public function reset_otp(): View
    {

        return view('pages.verifyResetOtp');

    }

    /**
     * return file to download
     * @param $product_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download_product_guide($product_id): BinaryFileResponse
    {

        $media = Product::find($product_id)->getFirstMedia('product_guide');

        return response()->download($media->getPath(), $media->file_name);

    }
}
