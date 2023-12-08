<?php

namespace App\Http\Requests\web\Order;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartWebRequest extends FormRequest
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
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|int',
        ];
    }
}
