<?php

namespace App\Http\Requests\web\order;

use Illuminate\Foundation\Http\FormRequest;

class CheckCouponValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'coupon_code'=>'sometimes|exists:coupons,code',
            'type'=>'sometimes|string'
        ];
    }
}
