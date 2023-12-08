<?php

namespace App\Http\Controllers\Api\Order;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\AddToCartRequest;
use App\Http\Requests\Api\Order\DeleteCartItemRequest;
use App\Http\Requests\Api\Order\UpdateCartRequest;
use App\Models\Product;
use App\Settings\GeneralSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends ApiController
{

    /**
     * @param AddToCartRequest $request
     * @param GeneralSettings $settings
     * @return JsonResponse
     * @throws \Darryldecode\Cart\Exceptions\InvalidConditionException
     * Add Item to cart +tax price from subtotal
     */
    public function AddToCart(AddToCartRequest $request, GeneralSettings $settings): JsonResponse
    {


        $tax = $settings->tax_price;
        $product = Product::find($request->validated('product_id'));
        \Cart::session($this->user->id)->add(array(
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
        \Cart::session($this->user->id)->condition($condition2);
        $items = \Cart::session($this->user->id)->getContent()->toArray();
        $cart = array_values($items);

        return ApiResponse::ResponseSuccess(data: [
            'total' => round(\Cart::session($this->user->id)->getTotal(), 2),
            'sub_total' => \Cart::session($this->user->id)->getSubTotal(),
            'tax' => $settings->tax_price . '%',
            'cart' => $cart
        ]);
    }

    /**
     * @param UpdateCartRequest $request
     * @param GeneralSettings $settings
     * @return JsonResponse
     * update cart item
     */

    public function updateCart(UpdateCartRequest $request, GeneralSettings $settings): JsonResponse
    {

        $product = Product::find($request->validated('product_id'));


        \Cart::session($this->user->id)->update($request->validated('product_id'), [
            'quantity' => array(
                'relative' => false,
                'value' => $request->validated('quantity')
            ),
        ]);
        $items = \Cart::session($this->user->id)->getContent()->toArray();
        $cart = array_values($items);
        return ApiResponse::ResponseSuccess(data: [
            'cart' => [
                'total' => \Cart::session($this->user->id)->getTotal(),
                'sub_total' => \Cart::session($this->user->id)->getSubTotal(),
                'tax' => $settings?->tax_price,
                'cart' => $cart
            ]
        ]);

    }

    /**
     * @param DeleteCartItemRequest $request
     * @param GeneralSettings $settings
     * @return JsonResponse
     */
    public function deleteItem(DeleteCartItemRequest $request, GeneralSettings $settings): JsonResponse
    {

        \Cart::session($this->user->id)->remove($request->validated('product_id'));
        $items = \Cart::session($this->user->id)->getContent()->toArray();
        $cart = array_values($items);

        return ApiResponse::ResponseSuccess(data: [
            'cart' => [
                'total' => \Cart::session($this->user->id)->getTotal(),
                'sub_total' => \Cart::session($this->user->id)->getSubTotalWithoutConditions(),
                'tax' => $settings?->tax_price,
                'cart' => $cart
            ]
        ]);
    }


    /**
     * @param GeneralSettings $settings
     * @return JsonResponse
     */
    public function clearCart(): JsonResponse
    {


        \Cart::session($this->user->id)->clear();
        $items = \Cart::session($this->user->id)->getContent()->toArray();
        $cart = array_values($items);
        return ApiResponse::ResponseSuccess(data: [
            'cart' => $cart

        ]);

    }

    public function get_cart(GeneralSettings $settings): JsonResponse
    {
        $items = \Cart::session($this->user->id)->getContent()->toArray();

        $cart = array_values($items);
        $cartConditions = \Cart::session($this->user->id)->getConditions();

        $conditions = [];
        foreach ($cartConditions as $condition) {
            $oneCondition = [];
            data_set($oneCondition, 'condition_name', $condition->getName());
            data_set($oneCondition, 'condition_type', $condition->getType());
            data_set($oneCondition, 'condition_value', $condition->getValue());
            array_push($conditions,  $oneCondition);
        }

        return ApiResponse::ResponseSuccess(data: [
            'total' => round(\Cart::session($this->user->id)->getTotal(),2),
            'sub_total' => \Cart::session($this->user->id)->getSubTotalWithoutConditions(),
            'cartConditions' => $conditions,
            'tax' => $settings->tax_price . '%',
            'cart' => $cart,
        ]);
    }

}
