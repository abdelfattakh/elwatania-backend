<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\ProviderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !auth('users')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
//            'provider' => ['required', 'string', 'min:0', 'max:191', Rule::in([ProviderEnum::facebook()->value, ProviderEnum::google()->value, ProviderEnum::twitter()->value, ProviderEnum::apple()->value])],
//            'access_token' => ['required', 'string', 'min:0'],
//            'access_secret' => ['nullable', 'string', 'min:0'],
        ];
    }
}
