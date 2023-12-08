<?php

namespace App\Http\Controllers\web\Auth;

use App\Enums\ProviderEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\SocialAuthRequest;
use App\Models\Social;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{

    public function socialAuth($provider_name)
    {


        return Socialite::driver($provider_name)->redirect();
    }

    public function social_login($provider_name)
    {
        switch ($provider_name) {
            case ProviderEnum::facebook()->value:
                $socialite = Socialite::driver(ProviderEnum::facebook()->value)->fields(['name', 'first_name', 'last_name', 'email']);
                break;
            case ProviderEnum::google()->value:
                $socialite = Socialite::driver(ProviderEnum::google()->value)->scopes(['profile', 'email']);
                break;
        }

        try {
            if (config('services.' . $provider_name . '.oauth') == '1') {
                /** @var \Laravel\Socialite\One\AbstractProvider $socialite */
                $social_user = $socialite->userFromTokenAndSecret(
                    token: request('access_token'),
                    secret: request('access_secret'),

                );

            } else {
                $social_user = $socialite->user();

            }
        } catch (\Exception $e) {

            abort(401, __('auth.failed'));
        }
        $account = Social::query()
            ->where('provider', $provider_name)
            ->where('provider_user_id', $social_user->getId())
            ->where('sociable_type', (new User())->getMorphClass())
            ->with('sociable')
            ->first();


            $name = $social_user->getName();
            $splitted_name = explode(" ", $social_user->getName());

            $user = User::updateOrCreate([
                'email' => $social_user->getEmail()
            ],[
                'first_name' => $provider_name == ProviderEnum::google()->value ? $social_user['given_name'] ?? 'unknown' : $social_user['first_name'] ?? 'unknown',
                'last_name' =>  $provider_name == ProviderEnum::google()->value ? $social_user['family_name'] ?? 'unknown' : $social_user['last_name'] ?? 'unknown',
                'email' => $social_user->getEmail() ?? 'null',
            ]);
            event(new Registered($user));



        $user->socials()->create([
            'provider' => $provider_name,
            'provider_user_id' => $social_user->getId(),
        ]);


        if (!$user->hasVerifiedEmail() && $user->email == $social_user->getEmail()) {
            $user->markEmailAsVerified();
        }
        Auth::login($user);
         session()->remove('provider_name');
        return redirect()->route('home');
    }
}
