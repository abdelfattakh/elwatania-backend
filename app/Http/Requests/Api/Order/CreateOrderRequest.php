<?php

namespace App\Http\Requests\Api\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('users')->check();

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'coupon_id'=>'sometimes|exists:coupons,id',
            'payment_method'=>'required|exists:payment_methods,id',
            'address_id'=>'required|exists:addresses,id'
        ];
    }
}
