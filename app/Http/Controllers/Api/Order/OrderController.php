<?php

namespace App\Http\Controllers\Api\Order;

use App\Enums\CouponTypeEndpointEnum;
use App\Enums\CouponTypeEnum;
use App\Enums\OrderStatusEnum;
use App\Events\NewOrderEvent;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\CancelOrderRequest;
use App\Http\Requests\Api\Order\CheckCouponValidationRequest;
use App\Http\Requests\Api\Order\CheckoutCartRequest;
use App\Http\Requests\Api\Order\CreateOrderRequest;
use App\Models\Address;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Settings\GeneralSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    /**
     * @param CreateOrderRequest $request
     * @param GeneralSettings $settings
     * @return JsonResponse
     */
    public function create_order(CreateOrderRequest $request, GeneralSettings $settings): JsonResponse
    {

        $items = \Cart::session($this->user->id)->getContent();
        $products = [];
        foreach ($items as $row) {

            $product = [];
            data_set($product, 'product_id', $row->associatedModel->id);
            data_set($product, 'product_name', $row->associatedModel->name);
            data_set($product, 'quantity', $row->quantity);
            data_set($product, 'product_price',  Product::find($row->associatedModel->id)->final_price);
            array_push($products, $product);
        }

        $payment_method = PaymentMethod::find($request->validated('payment_method'));
        $coupon = Coupon::find($request->validated('coupon_id'));
        $address = Address::find($request->validated('address_id'));

        $order = $this->user->orders()->create([
            'payment_method_id' => $request->validated('payment_method'),
            'address_id' => $address->id,
            'payment_method_name' => $payment_method->name,
            'coupon_id' => $request->validated('coupon_id'),
            'coupon_code' => $coupon?->code,
            'delivery_fees' => $address?->city->delivery_fees,
            'product_shipping_time' => $address?->area->shipping_time,
            'status' => OrderStatusEnum::inProgress()->value,
            'address_street_name' => $address->street_name,
            'address_phone' => $address->phone,
            'address_country_name' => $address?->country->name,
            'address_area_name' => $address?->area->name,
            'address_city_name' => $address?->city->name,
            'address_building_no' => $address->building_no,
            'address_flat_no' => $address->flat_no,
            'address_level' => $address->level,
            'tax_price' => $settings->tax_price,
            'total' => \Cart::session($this->user->id)->getTotal(),
            'sub_total' => \Cart::session($this->user->id)->getSubTotal(),
        ]);
         event(new NewOrderEvent($order));

        $order->orderItems()->createMany($products);


        $this->decrease_quantity();
        $this->decrease_coupon();
        \Cart::session($this->user->id)->clear();
        \Cart::session($this->user->id)->clearCartConditions();


        return ApiResponse::ResponseSuccess(data: [
            'order' => $order,
            'orderitem'=>$order->orderItems()->get(),

        ]);
    }

    /**
     * @param $products
     * @return void
     */
    private function decrease_quantity()
    {
        $items = \Cart::session($this->user->id)->getContent();
        foreach ($items as $row) {
            $row->associatedModel->update(['quantity' => $row->associatedModel->quantity - $row->quantity
            ]);
        }
    }


    /**
     * @param $products
     * @return void
     */
    private function decrease_coupon()
    {

        $couponconditions = \Cart::session($this->user->id)->getConditionsByType('coupon');
        $coupons = [];
        foreach ($couponconditions as $couponcondition) {
            array_push($coupons, $couponcondition->getName());
        }
        Coupon::whereIn('code', $coupons)->decrement('no_uses');


    }

    /**
     * @param CancelOrderRequest $request
     * @return JsonResponse
     */
    public function cancel_order(CancelOrderRequest $request,GeneralSettings $settings): JsonResponse
    {


        $orderItems = OrderItem::where('order_id', $request->validated('order_id'))->get();
        $products = [];
        foreach ($orderItems as $orderItem) {
            $product = [];
            data_set($product, 'product_id', $orderItem->product_id);
            data_set($product, 'quantity', $orderItem->quantity);
            array_push($products, $product);
        }

        $order = Order::find($request->validated('order_id'));
        $order->update([
            'status' => OrderStatusEnum::cancelled()->value
        ]);
        $this->return_product_quantity($products);
        if($order->coupon_id) {
            $this->return_coupon_uses($order);
        }

        return ApiResponse::ResponseSuccess(data: [
            'order' => $order
        ]);

    }

    /**
     * @param $products
     * @return void
     */
    private function return_product_quantity($products)
    {

        foreach ($products as $productdata) {
            $product = Product::find($productdata['product_id']);
            $product->update(['quantity' => $product->quantity + $productdata['quantity']]);
        }

    }

    /**
     * @param $order
     * @return void
     */

    private function return_coupon_uses($order)
    {
        Coupon::findOrFail($order?->coupon_id)->increment('no_uses');
    }

    /**
     * @param CheckCouponValidationRequest $request
     * @return JsonResponse
     */
    public function coupon_check(CheckCouponValidationRequest $request, GeneralSettings $settings): JsonResponse
    {
        $tax = $settings?->tax_price;
        $coupon = Coupon::where('code', $request->validated('coupon_code'))
            ->valid()
            ->first();
        $items = \Cart::session($this->user->id)->getContent()->toArray();
        $cart = array_values($items);
        if (!$coupon) {
            return ApiResponse::ResponseSuccess(message: "Coupon not valid");
        }

        if ($request->validated('type') == CouponTypeEndpointEnum::add_coupon()->value) {
            $condition = new \Darryldecode\Cart\CartCondition(array(
                'name' => $coupon->code,
                'type' => 'coupon',
                'tax' => "$tax%",
                'target' => 'subtotal',
                'value' => $coupon->type == CouponTypeEnum::percentage()->value ? "-$coupon->value%" : "-$coupon->value",
            ));

            \Cart::condition($condition);
            \Cart::session($this->user->id)->condition($condition);

        } elseif ($request->validated('type') == CouponTypeEndpointEnum::remove_coupon()->value) {
            \Cart::session($this->user->id)->removeCartCondition($coupon->code);
        }

        return ApiResponse::ResponseSuccess(data: [
            'total' => round(\Cart::session($this->user->id)->getTotal(), 2),
            'sub_total' => \Cart::session($this->user->id)->getSubTotalWithoutConditions(),
            'coupon_value' => $coupon->type == CouponTypeEnum::percentage()->value ? "-$coupon->value%" : "-$coupon->value",
            'tax' => "$tax%",
            'coupon' => $coupon,
            'cart' => $cart
        ]);
    }

    /**
     * @param CheckoutCartRequest $request
     * @param GeneralSettings $settings
     * @return JsonResponse
     */
    public function checkout_cart(CheckoutCartRequest $request, GeneralSettings $settings): JsonResponse
    {
        $address = Address::find($request->validated('address_id'));
        $coupon = Coupon::find($request->validated('coupon_id'));
        $delivery_fees = $address->city->delivery_fees;
        $tax = $settings->tax_price;
        $items = \Cart::session($this->user->id)->getContent()->toArray();
        $cart = array_values($items);

        $condition1 = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'delivery',
            'type' => 'delivery',
            'target' => 'total',
            'value' => $delivery_fees,
        ));

        \Cart::condition($condition1);
        \Cart::session($this->user->id)->condition($condition1);
        if (!$coupon) {
            return ApiResponse::ResponseSuccess(data: [
                'total' => \Cart::session($this->user->id)->getTotal(),
                'delivery_fees' => $delivery_fees,
                'sub_total' => \Cart::session($this->user->id)->getSubTotal(),
                'tax' => $settings->tax_price . '%',
                'cart' => $cart
            ]);

        }
        return ApiResponse::ResponseSuccess(data: [
            'total' => \Cart::session($this->user->id)->getTotal(),
            'delivery_fees' => $delivery_fees,
            'sub_total' => \Cart::session($this->user->id)->getSubTotal(),
            'tax' => $settings->tax_price . '%',
            'coupon_value' => $coupon?->type == CouponTypeEnum::percentage()->value ? "-$coupon->value%" : "-$coupon->value",
            'coupon' => $coupon,
            'cart' => $cart
        ]);

    }

    /**
     * @return JsonResponse
     * get user orders
     */
  public function get_orders():JsonResponse
  {

      return ApiResponse::ResponseSuccess(data:[
         'orders'=>$this->user->orders()->with('orderItems')->get()
      ]);
  }
}
