<?php

namespace App\Http\Requests\web\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateWebOrderRequest extends FormRequest
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
            'payment_method'=>'sometimes|exists:addresses,id',
            'address_id'=>'sometimes|exists:addresses,id'
      ];
    }
}
