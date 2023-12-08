<?php

namespace App\Traits\Verify;

use App\Notifications\ResetWithOTP;
use App\Notifications\VerifyWithOTP;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait CanResetPasswordOTP
{
    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset(): string
    {
        return $this->getEmailForVerification(); // same email as verify.
    }

    /**
     * Get the phone number where password reset links are sent.
     *
     * @return string
     */
    public function getPhoneForPasswordReset(): string
    {
        return $this->getPhoneForVerification(); // same phone as verify.
    }

    /**
     * Generate Reset Token.
     *
     * @param string $username
     * @return string
     */
    public function onResetPasswordVerified(string $username): string
    {
        $token = Str::orderedUuid();
        DB::table('password_resets')->insert(['email' => $username, 'token' => $token]);
        return $token;
    }

    /**
     * Send the password reset notification.
     *
     * @param string $username
     * @return void
     */
    public function sendPasswordResetOTPNotification(string $username): void
    {
        $type = is_email($username) ? 'email' : 'phone';

        $otp = $this->newOTP(username: $username, type: 'reset_' . $type);
        $this->notify(new ResetWithOTP(otp: $otp));
    }
}
