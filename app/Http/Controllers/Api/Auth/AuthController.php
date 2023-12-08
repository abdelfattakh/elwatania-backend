<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\OTPTypeEnum;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\changeUserPasswordRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordVerifyRequest;
use App\Http\Requests\Api\Auth\UpdateRequest;
use App\Http\Requests\Api\Auth\VerifyUserRequest;
use App\Models\OTP;
use App\Notifications\GeneralNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @group Auth
 */
class AuthController extends ApiController
{
    /**
     * Register New User.
     * Sends Email with OTP to user's email.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->user = $this->authModel::query()->create(array_merge($validated, ['password' => Hash::make($validated['password'])]));

        event(new Registered($this->user));
        $this->user->newEmailOTP($this->user->getEmailForVerification());

        $token = $this->user->createToken('registration')->plainTextToken;

        return ApiResponse::ResponseSuccess([
            'message' => 'verification code will send through email',
            'user' => $this->user,
            'token' => $token,
        ]);
    }

    /**
     * Verify User Account.
     *
     * @param VerifyUserRequest $request
     * @return JsonResponse
     * Send token to email after get user from set user function
     * verify that if this is not the user or have valid verify code then this user will fail
     */
    public function verifyUserEmail(VerifyUserRequest $request): JsonResponse
    {
        $this->setUserTo($request->validated('username'));

        if (!filled($this->user)) {
            return ApiResponse::ResponseFail(message: __('auth.failed'), statusCode: 401);
        }

        $otp = $this->user->getVerificationCode($request->validated('username'), $request->validated('code'));

        if (!$otp) {
            return ApiResponse::ResponseFail(
                data: [
                    'message' => __('auth.failed'),
                    'DEBUG-OTP' => config('app.debug') ? $this->user->otps()->valid()->get() : 'OMITTED',
                ],
                message: __('auth.failed'),
                statusCode: 422,
            );
        }

        $this->user->refresh();
        return ApiResponse::ResponseSuccess([
            'user' => $this->user,
            'reset_token' => $this->user->applyOTPAction($otp),
            'DEBUG-OTP' => config('app.debug') ? $this->user->otps()->valid()->get() : 'OMITTED',
        ]);
    }

    /**
     * Login user Account
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $this->setUserTo($request->validated('username'));

        if (!filled($this->user) || !(Hash::check($request->validated('password'), $this->user->password))) {
            return ApiResponse::ResponseFail(message: __('auth.failed'), statusCode: 422);
        }

        event(new Login(guard: $this->guard, user: $this->user, remember: true));

        return ApiResponse::ResponseSuccess(data: [
            'user' => $this->user,
            'token' => $this->user->createToken('login')->plainTextToken,
            'DEBUG-OTP' => config('app.debug') ? $this->user->otps()->valid()->get() : 'OMITTED',
        ]);
    }

    public function updateProfile(UpdateRequest $request): JsonResponse
    {
        if ($request->has('current_password')) {
            if ($request->has('current_password') && Hash::check($request->input('current_password'), $this->user->password)) {
                $this->user->update([
                    'password' => Hash::make($request->validated('password')),
                ]);
                $this->user->notify(new GeneralNotification(title: 'Password Changed', body: 'Account Password Changed!.'));
            } else {
                return ApiResponse::ResponseFail(message: __('auth.password'), statusCode: 422);
            }
        }
        if ($request->has('first_name') && $this->user->first_name != $request->input('first_name')) {
            $this->user->update([
                'first_name' => $request->input('first_name') ?: $this->user->first_name,
            ]);
        }

        if ($request->has('last_name') && $this->user->last_name != $request->input('last_name')) {
            $this->user->update([
                'last_name' => $request->input('last_name') ?: $this->user->last_name,
            ]);
        }
        if ($request->has('email') && $this->user->email != $request->input('email')) {
            $this->user->newEmailOTP($request->input('email'));
        }

        return ApiResponse::ResponseSuccess(data: [
            'user' => $this->user->refresh()
        ]);

    }

    /**
     * Logout From Account
     *
     * @authenticated
     * @bodyParam from_all bool optional send to logout from all devices.
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->has('from_all')
            ? $this->user->tokens()->delete()
            : $this->user->currentAccessToken()->delete();

        return ApiResponse::ResponseSuccess(message: __('auth.logged_out'));
    }

    /**
     * Get Current User.
     *
     * @authenticated
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        return ApiResponse::ResponseSuccess(data: [
            'user' => $this->user
        ]);
    }

    /**
     * Reset Password
     * TODO(Saifallak): Pending Test.
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $this->setUserTo($request->validated('username'));

        if (!filled($this->user)) {
            return ApiResponse::ResponseFail(message: __('auth.failed'), statusCode: 401);
        }

        $this->user->sendPasswordResetOTPNotification(username: $request->validated('username'));

        return ApiResponse::ResponseSuccess([
            'message' => 'verification code send to your ' . $request->validated('username'),
            'DEBUG-OTP' => config('app.debug') ? $this->user->otps()->valid()->get() : 'OMITTED',
        ]);
    }

    /**
     * @param ResetPasswordVerifyRequest $request
     * @return JsonResponse
     */
    public function verifyResetPassword(ResetPasswordVerifyRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->setUserTo($request->validated('username'));

        /** @var OTP $otp */
        $otp = $this->user->otps()
            ->where('code', $validated['code'])
            ->where('target', $validated['username'])
            ->when(
                filter_var($request->validated('username'), FILTER_VALIDATE_EMAIL),
                fn($q) => $q->where('type', OTPTypeEnum::resetEmail()),
                fn($q) => $q->where('type', OTPTypeEnum::resetPhone()),
            )
            ->valid()
            ->first();

        if (!$otp) {
            return ApiResponse::ResponseFail(message: __('auth.failed'), statusCode: 422);
        }

        $otp->markAsUsed();

        $token = $this->user->createToken('reset_password')->plainTextToken;

        $resetToken = Str::random();

        DB::table('password_resets')->insert(['email' => $validated['username'], 'token' => $resetToken]);

        return ApiResponse::ResponseSuccess([
            'token' => $token,
            'reset_token' => $resetToken,
            'user' => $this->user->refresh()
        ]);
    }

    /**
     * TODO(Saifallak): Pending Test.
     * @param changeUserPasswordRequest $request
     * @return JsonResponse
     */

    public function changePassword(changeUserPasswordRequest $request): JsonResponse
    {
        $this->setUserTo($request->validated('username'));

        if ($request->has('reset_token')) {
            $cond = DB::table('password_resets')
                ->where('email', $request->validated('username'))
                ->where('token', $request->validated('reset_token'))
                ->exists();

            if (!$cond) {
                return ApiResponse::ResponseFail(message: __('auth.password'), statusCode: 422);
            }
            $this->user->update([
                'password' => Hash::make($request->validated('password')),
            ]);
            $this->user->notify(new GeneralNotification(title: 'Password Changed', body: 'Account Password Changed!.'));
        }
        return ApiResponse::ResponseSuccess([
            'message' => 'your password has been reset',
            'user' => $this->user->refresh()
        ]);

    }
}
