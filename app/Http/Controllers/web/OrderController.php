<?php

namespace App\Http\Controllers\web;

use App\Enums\OrderStatusEnum;
use App\Events\NewOrderEvent;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\CreateOrderRequest;
use App\Http\Requests\web\order\CreateReviewRequest;
use App\Http\Requests\web\Order\CreateWebOrderRequest;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Settings\GeneralSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    /**
     * create Order
     * @param CreateWebOrderRequest $request
     * @param GeneralSettings $settings
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_order(CreateWebOrderRequest $request, GeneralSettings $settings): RedirectResponse
    {
        $couponCondition = \Cart::session(auth()->user()->id)->getConditionsByType('coupon');
        if (filled($couponCondition)) {
            $coupon_code = head(array_keys(head($couponCondition)));
            $coupon_id = Coupon::where('code', $coupon_code)->first()->id;
            $coupon = Coupon::find($coupon_id);
        }

        $items = \Cart::session(auth()->user()->id)->getContent();
        $products = [];
        foreach ($items as $row) {

            $product = [];
            data_set($product, 'product_id', $row->associatedModel->id);
            data_set($product, 'product_name', $row->associatedModel->name);
            data_set($product, 'quantity', $row->quantity);
            data_set($product, 'product_price', Product::find($row->associatedModel->id)->final_price);
            array_push($products, $product);
        }

        $payment_method = PaymentMethod::find($request->validated('payment_method'));


        $address = Address::find($request->validated('address_id'));

        $order = auth()->user()->orders()->create([
            'payment_method_id' => $payment_method->id,
            'address_id' => $address->id,
            'payment_method_name' => $payment_method->name,
            'coupon_id' =>$coupon_id??null,
            'coupon_code' => $coupon->code??null,
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
            'total' => \Cart::session(auth()->user()->id)->getTotal(),
            'sub_total' => \Cart::session(auth()->user()->id)->getSubTotal(),
        ]);
        event(new NewOrderEvent($order));

        $order->orderItems()->createMany($products);


        $this->decrease_quantity();
        $this->decrease_coupon();

        \Cart::session(auth()->user()->id)->clear();
        \Cart::session(auth()->user()->id)->clearCartConditions();

        return redirect()->route('request');

    }


    /**
     * Decrease Order Quantity after Create Order
     * @return void
     */
    private function decrease_quantity()
    {
        $items = \Cart::session(auth()->user()->id)->getContent();
        foreach ($items as $row) {
            $row->associatedModel->update(['quantity' => $row->associatedModel->quantity - $row->quantity
            ]);
        }
    }


    /** Decrease Coupon No of uses after create order
     * @return void
     */
    private function decrease_coupon()
    {

        $couponconditions = \Cart::session(auth()->user()->id)->getConditionsByType('coupon');
        $coupons = [];
        foreach ($couponconditions as $couponcondition) {
            array_push($coupons, $couponcondition->getName());
        }
        Coupon::whereIn('code', $coupons)->decrement('no_uses');


    }

    /**.
     * create Review
     * @param Request $request
     * @return void
     */
    public function create_review(CreateReviewRequest $request, $product_id): RedirectResponse
    {

        $product = Product::find($product_id);
        $review = $product->reviews()->updateOrCreate(
            ['reviewable_id' => $product_id], [
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->first_name,
            'comment' => $request['comment'],
            'stars' => $request['stars'],
            'reviewable_id' => $product_id,
            'reviewable_type' => get_class($product)
        ]);
        return redirect()->back();
    }

}
