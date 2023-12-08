<?php

namespace App\Http\Requests\web\Address;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'name' => 'required|string',
            'family_name' => 'required|string',
            'street_name' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'area_id' => 'required|exists:areas,id',
            'building_no' => 'required|int',
            'level' => 'required|int',
            'flat_no' => 'required|int',
            'phone'=>'required'
        ];
    }
}
