<?php

namespace App\Traits\Verify;

use App\Notifications\VerifyWithOTP;
use Illuminate\Auth\Events\Verified;

trait CanChangeEmail
{
    /**
     * User Change His Email.
     *
     * @param string $email
     * @param bool $markAsVerified
     * @return void
     */
    public function newEmail(string $email, bool $markAsVerified = false): void
    {
        if ($this->getEmailForVerification() === $email && $this->hasVerifiedEmail()) {
            return;
        }

        if ($markAsVerified) {
            $this->onNewEmailOTPVerified($email);
            return;
        }

        $this->sendPendingEmailVerificationNotification($email);
    }

    /**
     * On New Email OTP Verified.
     *
     * @param string $email
     * @return ?string
     */
    public function onNewEmailOTPVerified(string $email): ?string
    {
        $this->update(['email' => $email]);
        return $this->onEmailOTPVerified();
    }

    /**
     * On Email OTP Verified.
     *
     * @return ?string
     */
    public function onEmailOTPVerified(): ?string
    {
        if (!$this->hasVerifiedEmail()) {
            $this->markEmailAsVerified();
            event(new Verified($this));
        }
        return null;
    }

    /**
     * Sends the VerifyWithOTP to the new email address.
     *
     * @param ?string $pendingUserEmail
     * @param string $type
     * @return void
     */
    public function sendPendingEmailVerificationNotification(?string $pendingUserEmail, string $type = 'new_email'): void
    {
        if (!filled($pendingUserEmail)) {
            return;
        }
        $otp = $this->newOTP(username: $pendingUserEmail, type: $type);
        $this->notify(new VerifyWithOTP(otp: $otp));
    }

    /**
     * Sends the VerifyWithOTP to the current email address.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->sendPendingEmailVerificationNotification($this->getEmailForVerification(), 'verify_email');
    }
}
