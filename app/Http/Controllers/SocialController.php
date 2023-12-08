<?php

namespace App\Http\Controllers;

use App\Enums\ProviderEnum;
use App\Http\Requests\Api\Auth\SocialAuthRequest;
use App\Models\Social;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * @param SocialAuthRequest $request
     * @return JsonResponse
     */
    public function socialAuth(SocialAuthRequest $request): JsonResponse
    {

        switch ($request->validated('provider')) {
            case ProviderEnum::facebook()->value:
                $socialite = Socialite::driver(ProviderEnum::facebook()->value)->fields(['name', 'first_name', 'last_name', 'email']);
                break;
            case ProviderEnum::google()->value:
                $socialite = Socialite::driver(ProviderEnum::google()->value)->scopes(['profile', 'email']);
                break;
            case ProviderEnum::twitter()->value:
                $socialite = Socialite::driver(ProviderEnum::twitter()->value);
                break;
            case ProviderEnum::apple()->value:
                $socialite = Socialite::driver(ProviderEnum::apple()->value);
                break;
        }
        try {
            if (config('services.' . $request->validated('provider') . '.oauth') == '1') {
                /** @var \Laravel\Socialite\One\AbstractProvider $socialite */
                $social_user = $socialite->userFromTokenAndSecret(
                    token: $request->validated('access_token'),
                    secret: $request->validated('access_secret'),
                );
            } else {
                /** @var \Laravel\Socialite\Two\AbstractProvider $socialite */
                $social_user = $socialite->userFromToken(
                    token: $request->validated('access_token'),

                );


            }


        } catch (\Exception $e) {
            abort(401, __('auth.failed'));
        }
        $account = Social::query()
            ->where('provider', $request->validated('provider'))
            ->where('provider_user_id', $social_user->getId())
            ->where('sociable_type', (new User())->getMorphClass())
            ->with('sociable')
            ->first();
        $this->setUserTo($account?->sociable ?? $social_user->getEmail());
        if (!$this->user) {
            $name = $social_user->getName();
            $splitted_name= explode(" ",$social_user->getName());

            $this->user = User::create([
                'first_name' =>   $request->validated('provider')== ProviderEnum::google()->value?$social_user['given_name'] ?? 'unknown':$social_user['first_name'] ?? 'unknown',
                'last_name' =>   $request->validated('provider')== ProviderEnum::google()->value?$social_user['family_name'] ?? 'unknown':$social_user['last_name'] ?? 'unknown',
                'email' => $social_user->getEmail() ?? 'null',
            ]);
            event(new Registered($this->user));
        }


        $this->user->socials()->create([
            'provider' => $request->validated('provider'),
            'provider_user_id' => $social_user->getId(),
        ]);

        $token = $this->user->createToken('socialToken')->plainTextToken;
        if (!$this->user->hasVerifiedEmail() && $this->user->email == $social_user->getEmail()) {
            $this->user->markEmailAsVerified();
        }

        return response()->json([
            'token' => $token,
            'user' => $this->user->fresh(),
        ]);
    }
}

