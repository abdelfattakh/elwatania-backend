<?php

namespace App\Traits\Verify;

use Illuminate\Database\Eloquent\Builder;

trait MustVerifyPhone
{
    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified(): bool
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the phone verification notification.
     *
     * @return void
     */
    public function sendPhoneVerificationNotification(): void
    {
        $this->sendPendingPhoneVerificationNotification($this->getAttribute('phone'), 'verify_phone');
    }

    /**
     * Get the phone address that should be used for verification.
     *
     * @return ?string
     */
    abstract public function getPhoneForVerification(): ?string;

    /**
     * Determine if the user has verified their phone address.
     *
     * @return bool
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * Scope of Has Phone Number Like.
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    abstract public function scopeHasPhoneLike(Builder $query, string $search): Builder;

    /**
     * Scope of Has Phone Number.
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    abstract public function scopeHasPhone(Builder $query, string $search): Builder;
}
