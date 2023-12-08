<?php

namespace App\Http\Controllers\Web\Auth;

use App\Enums\OTPTypeEnum;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Web\WebController;
use App\Http\Requests\Api\Auth\changeUserPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordVerifyRequest;
use App\Http\Requests\Api\Auth\UpdateRequest;
use App\Http\Requests\web\Auth\ChangePasswordRequest;
use App\Http\Requests\web\Auth\LoginRequest;
use App\Http\Requests\web\Auth\OtpValidationRequest;
use App\Http\Requests\web\Auth\RegisterRequest;
use App\Http\Requests\web\Auth\updateProfileRequest;
use App\Models\OTP;
use App\Notifications\GeneralNotification;
use http\Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends WebController
{
    /**
     * Register New User.
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->user = $this->authModel::query()->create(array_merge($validated, ['password' => Hash::make($validated['password'])]));

        event(new Registered($this->user));

        return redirect()->back()->with('message', __('web.register_success'));
    }

    /**
     * Login web User.
     *
     * @param LoginRequest $request
     * Auth::login($user) ->to create session
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $this->setUserTo($request->validated('email'));

        if (!$this->user || !Hash::check($request->validated('password'), $this->user->getAuthPassword())) {
            return redirect()->back()->withErrors(['msg' => __('auth.failed')]);
        }

        if (!$this->user->hasVerifiedEmail()) {
            session()->flash('message', __('web.register_success'));
            session()->put('user', $this->user);
            return redirect()->back();
        }


        auth($this->guard)->login($this->user);

        return redirect()->route('home');
    }

    /**
     * Logout User.
     *
     * @param Request $request
     * @return RedirectResponse
     * destroy token
     * then invalidate session
     * then regenerate CSRF token
     */
    public function logout(Request $request): RedirectResponse
    {
        auth($this->guard)->logout();

        $request->session()->flush();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Update Profile
     * @param updateProfileRequest $request
     * @return RedirectResponse
     */
    public function update_profile(updateProfileRequest $request): RedirectResponse
    {


        if ($request->has('first_name') && auth()->user()->first_name != $request->validated('first_name')) {
            auth()->user()->update([
                'first_name' => $request->validated('first_name') ?: auth()->user()->first_name,
            ]);
        }

        if ($request->has('last_name') && auth()->user()->last_name != $request->validated('last_name')) {
            auth()->user()->update([
                'last_name' => $request->validated('last_name') ?: auth()->user()->last_name,
            ]);
        }

        return redirect()->back();
    }

    /**
     * change password
     * @return void
     */
    public function change_password(ChangePasswordRequest $request): RedirectResponse
    {
        if ($request->has('current_password')) {
            if ($request->has('current_password') && Hash::check($request->input('current_password'), auth()->user()->password)) {
                auth()->user()->update([
                    'password' => Hash::make($request->validated('password')),
                ]);
                auth()->user()->notify(new GeneralNotification(title: 'Password Changed', body: 'Account Password Changed!.'));
            } else {
                return redirect()->back()->withErrors(['message' => " __('auth.password')"]);
            }

        }
        return redirect()->route('logout');

    }

    /**
     * render change email page
     */
    public function change_email(): View
    {

        return view('pages.emailChange');
    }

    /**
     * update auth user email
     * @param OtpValidationRequest $request
     * @return RedirectResponse
     * @return RedirectResponse
     */
    public function change_email_submit(OtpValidationRequest $request): RedirectResponse
    {

        auth()->user()->newEmail($request->validated('email'));
        session()->flash('message', 'Please check your email to update your account');

        return redirect()->back();
    }

    /**
     * send reset password email
     * @param \App\Http\Requests\web\Auth\ResetPasswordRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function reset_password(\App\Http\Requests\web\Auth\ResetPasswordRequest $request): RedirectResponse
    {
        $this->setUserTo($request->validated('email'));

        if (!filled($this->user)) {
            return ApiResponse::ResponseFail(message: __('auth.failed'), statusCode: 401);
        }
        session()->put('user', $this->user);
        $this->user->sendPasswordResetOTPNotification(username: $request->validated('email'));

        return redirect()->route('auth.ResetOTP');
    }

    /**
     * verify otp send to mail to reset password
     * @param \App\Http\Requests\web\Auth\ResetPasswordVerifyRequest $request
     * @return RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function verifyResetPassword(\App\Http\Requests\web\Auth\ResetPasswordVerifyRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $email = session()->get('user')->email;
        $this->setUserTo($email);

        /** @var OTP $otp */
        $otp = $this->user->otps()
            ->where('code', $validated['code'])
            ->where('target', $email)
            ->where('type', OTPTypeEnum::reset_email()->value)
            ->valid()
            ->first();

        if (!$otp) {

            return redirect()->back()->withErrors(['error' => __('web.invalid_code')]);
        }

        $otp->markAsUsed();

        $token = $this->user->createToken('reset_password')->plainTextToken;

        $resetToken = Str::random();

        DB::table('password_resets')->insert(['email' => $email, 'token' => $resetToken]);
        session()->put('reset_token', $resetToken);

        return redirect()->route('auth.verifyResetCode');
    }

    /**
     * to change password
     * @param \App\Http\Requests\web\Auth\changeUserPasswordRequest $request
     * @return RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function change_password_forget(\App\Http\Requests\web\Auth\changeUserPasswordRequest $request): RedirectResponse
    {
        if (filled(session()->get('user')) && filled(session()->get('reset_token'))) {
            $email = session()->get('user')->email;
            $reset_token = session()->get('reset_token');
            $this->setUserTo($email);
        }
        if (filled(session()->get('reset_token'))) {
            $cond = DB::table('password_resets')
                ->where('email', $email)
                ->where('token', $reset_token)
                ->exists();

            if (!$cond ) {
                return redirect()->back()->withErrors(['error' => __('auth.password')]);
            }
            $this->user->update([
                'password' => Hash::make($request->validated('password')),
            ]);
            $this->user->notify(new GeneralNotification(title: 'Password Changed', body: 'Account Password Changed!.'));
        }
        if(!filled($this->user)){
            return redirect()->back()->withErrors(['error' => __('auth.password')]);

        }
        session()->remove('user');
        session()->remove('reset_token');
        return redirect()->route('login');

    }

}
