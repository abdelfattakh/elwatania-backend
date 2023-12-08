<?php

namespace App\Http\Requests\Api\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'name' => 'sometimes|string',
            'family_name' => 'sometimes|string',
            'street_name' => 'sometimes|string',
            'city_id' => 'sometimes|exists:cities,id',
            'area_id' => 'sometimes|exists:areas,id',
            'address_phone' => 'sometimes|string',
            'building_no' => 'sometimes|int',
            'level' => 'sometimes|int',
            'flat_no' => 'sometimes|int',
        ];
    }
}
