<?php

namespace App\Contracts\Verify;

use Illuminate\Database\Eloquent\Builder;

interface MustVerifyPhone
{
    /**
     * Determine if the user has verified their phone.
     *
     * @return bool
     */
    public function hasVerifiedPhone(): bool;

    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified(): bool;

    /**
     * Send the phone verification notification.
     *
     * @return void
     */
    public function sendPhoneVerificationNotification(): void;

    /**
     * Get the phone address that should be used for verification.
     *
     * @return ?string
     */
    public function getPhoneForVerification(): ?string;

    /**
     * Scope of Has Phone Number Like.
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeHasPhoneLike(Builder $query, string $search): Builder;

    /**
     * Scope of Has Phone Number.
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeHasPhone(Builder $query, string $search): Builder;
}
