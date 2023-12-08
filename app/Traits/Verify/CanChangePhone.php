<?php

namespace App\Traits\Verify;

use App\Notifications\VerifyWithOTP;
use Illuminate\Auth\Events\Verified;
use Illuminate\Database\Eloquent\Builder;

trait CanChangePhone
{
    /**
     * User Change Change His Phone.
     *
     * @param string $phone
     * @param bool $markAsVerified
     * @return void
     */
    public function newPhone(string $phone, bool $markAsVerified = false): void
    {
        if ($this->getPhoneForVerification() === $phone && $this->hasVerifiedPhone()) {
            return;
        }

        if ($markAsVerified) {
            $this->onNewPhoneOTPVerified($phone);
            return;
        }

        $this->sendPendingPhoneVerificationNotification($phone);
    }

    /**
     * Get the phone address that should be used for verification.
     *
     * @return ?string
     */
    public function getPhoneForVerification(): ?string
    {
        return $this->phone;
    }

    /**
     * On New Phone OTP Verified.
     *
     * @param string $phone
     * @return ?string
     */
    public function onNewPhoneOTPVerified(string $phone): ?string
    {
        $this->update(['phone' => $phone]);
        return $this->onPhoneOTPVerified();
    }

    /**
     * On Phone OTP Verified.
     *
     * @return ?string
     */
    public function onPhoneOTPVerified(): ?string
    {
        if (!$this->hasVerifiedPhone()) {
            $this->markPhoneAsVerified();
            event(new Verified($this));
        }
        return null;
    }

    /**
     * Sends the VerifyWithOTP to the new phone number.
     *
     * @param ?string $pendingUserPhone
     * @param string $type
     * @return void
     */
    public function sendPendingPhoneVerificationNotification(?string $pendingUserPhone, string $type = 'new_phone'): void
    {
        if (!filled($pendingUserPhone)) {
            return;
        }
        $otp = $this->newOTP(username: $pendingUserPhone, type: $type);
        $this->notify(new VerifyWithOTP(otp: $otp));
    }

    /**
     * Scope of Has Phone Number Like.
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeHasPhoneLike(Builder $query, string $search): Builder
    {
        return $query->orWhere('phone', 'LIKE', preg_replace('[^+0-9]', '', $search) . '%');
    }

    /**
     * Scope of Has Phone Number.
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeHasPhone(Builder $query, string $search): Builder
    {
        return $query->where('phone', $search);
    }
}
