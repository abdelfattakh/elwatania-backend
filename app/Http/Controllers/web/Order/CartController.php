<?php

namespace App\Http\Controllers\Web\Order;

use App\Enums\CouponTypeEndpointEnum;
use App\Enums\CouponTypeEnum;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\AddToCartRequest;
use App\Http\Requests\web\Order\CheckCouponValidationRequest;
use App\Http\Requests\Api\Order\DeleteCartItemRequest;
use App\Http\Requests\Api\Order\UpdateCartRequest;
use App\Http\Requests\web\Order\AddToCartWebRequest;
use App\Models\Coupon;
use App\Models\Product;
use App\Settings\GeneralSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{

    /**
     * Add Product to the cart
     * @param AddToCartWebRequest $request
     * @param GeneralSettings $settings
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Darryldecode\Cart\Exceptions\InvalidConditionException
     */
    public function AddToCart(AddToCartWebRequest $request, GeneralSettings $settings)
    {

        $tax = $settings->tax_price;

        $product = Product::find($request->validated('product_id'));

        \Cart::session(auth()->user()->id)->add(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->final_price,
            'quantity' => $request->validated('quantity'),
            'associatedModel' => $product
        ));

        $condition2 = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'VAT' . $tax . '%',
            'type' => 'tax',
            'target' => 'total',
            'value' => $tax . '%',
        ));
        \Cart::condition($condition2);
        \Cart::session(auth()->user()->id)->condition($condition2);
        $items = \Cart::session(auth()->user()->id)->getContent()->toArray();
        $cart = array_values($items);


        return redirect()->route('basket', compact('cart'));
    }

    /**
     * Toggle between add to cart and remove from cart
     * @param AddToCartWebRequest $request
     * @param GeneralSettings $settings
     * @return JsonResponse
     * @throws \Darryldecode\Cart\Exceptions\InvalidConditionException
     */

    public function toggle_cart(AddToCartWebRequest $request, GeneralSettings $settings)
    {

        if (filled(\Cart::session(auth()->user()->id)->get($request->validated('product_id')))) {
            \Cart::session(auth()->user()->id)->remove($request->validated('product_id'));
            return ApiResponse::ResponseSuccess(data: [
                'create' => false,
            ],
                message: "product removed from cart  successfully");

        } else {
            $tax = $settings->tax_price;

            $product = Product::find($request->validated('product_id'));

            \Cart::session(auth()->user()->id)->add(array(
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->final_price,
                'quantity' => $request->validated('quantity'),
                'associatedModel' => $product
            ));

            $condition2 = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'VAT' . $tax . '%',
                'type' => 'tax',
                'target' => 'total',
                'value' => $tax . '%',
            ));
            \Cart::condition($condition2);
            \Cart::session(auth()->user()->id)->condition($condition2);
            $items = \Cart::session(auth()->user()->id)->getContent()->toArray();
            $cart = array_values($items);

        }
        return ApiResponse::ResponseSuccess(data: [
            'cart' => $cart,
            'create' => true,
        ], message: "your product Add to cart ");
    }

    /**
     * Remove item from cart
     * @param Request $request
     * @param GeneralSettings $settings
     * @return View
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function deleteItem(Request $request, GeneralSettings $settings): View
    {
        \Cart::session(auth()->user()->id)->remove($request['product_id']);
        $items = \Cart::session(auth()->user()->id)->getContent()->toArray();
        $cart = array_values($items);

        return view('pages.basket', [
            'total' => round(\Cart::session(auth()->user()->id)->getTotal(), 2),
            'sub_total' => \Cart::session(auth()->user()->id)->getSubTotal(),
            'tax' => $settings->tax_price . '%',
            'cart' => $cart,
        ]);
    }

    /** update cart
     * @param Request $request
     * @param GeneralSettings $settings
     * @return JsonResponse
     */
    public function updateCart(Request $request, GeneralSettings $settings): JsonResponse
    {
        $product = Product::find($request['product_id']);

        \Cart::session(auth()->user()->id)->update($request['product_id'], [
            'quantity' => array(
                'relative' => false,
                'value' => $request['quantity']
            ),
        ]);
        $items = \Cart::session(auth()->user()->id)->getContent()->toArray();
        $cart = collect(array_values($items))->sortBy('id');

        $html = view('pages.basket', [
            'total' => round(\Cart::session(auth()->user()->id)->getTotal(), 2),
            'sub_total' => \Cart::session(auth()->user()->id)->getSubTotal(),
            'tax' => $settings->tax_price . '%',
            'cart' => $cart
        ])->fragment('content');
        //Render to render view and converted to string

        return response()->json(['html' => $html]);


    }

    /**
     * Check for the coupon validation if it's valid apply it to the cart
     * @param CheckCouponValidationRequest $request
     * @param GeneralSettings $settings
     * @return JsonResponse
     * @throws \Darryldecode\Cart\Exceptions\InvalidConditionException
     */
    public function coupon_check(CheckCouponValidationRequest $request, GeneralSettings $settings): JsonResponse
    {

        $coupon = Coupon::where('code', $request['coupon_code'])
            ->valid()
            ->first();

        $items = \Cart::session(auth()->user()->id)->getContent()->toArray();
        $cart = array_values($items);
        if (!$coupon) {
            return ApiResponse::ResponseFail(message: "Coupon not valid");
        }
        if ($request['type'] == CouponTypeEndpointEnum::Apply()->value) {

            $condition = new \Darryldecode\Cart\CartCondition(array(
                'name' => $coupon->code,
                'type' => 'coupon',
                'tax' => "$settings?->tax_price%",
                'target' => 'subtotal',
                'value' => $coupon->type == CouponTypeEnum::percentage()->value ? "-$coupon->value%" : "-$coupon->value",
            ));
            \Cart::condition($condition);

            \Cart::session(auth()->user()->id)->condition($condition);


        } elseif ($request['type'] == CouponTypeEndpointEnum::remove()->value) {
            \Cart::session(auth()->user()->id)->removeCartCondition($request['coupon_code']);

            $html = view('pages.coupon', [
                'total' => round(\Cart::session(auth()->user()->id)->getTotal(), 2),
                'sub_total' => \Cart::session(auth()->user()->id)->getSubTotal(),
                'tax' => $settings->tax_price . '%',
                'cart' => $cart
            ])->render();
            //Render to render view and converted to string
            return response()->json(['html' => $html]);
        }


        $html = view('pages.coupon', [
            'total' => round(\Cart::session(auth()->user()->id)->getTotal(), 2),
            'sub_total' => \Cart::session(auth()->user()->id)->getSubTotal(),
            'coupon_value' => $coupon->type == CouponTypeEnum::percentage()->value ? "$coupon->value%" : "$coupon->value EGP",
            'coupon_code' => $coupon->code,
            'tax' => $settings->tax_price . '%',
            'cart' => $cart
        ])->render();
        //Render to render view and converted to string
        return response()->json(['html' => $html]);

    }
}
