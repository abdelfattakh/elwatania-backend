<?php

namespace App\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder verified(bool $isVerified = true)
 * @method static Builder unVerified()
 */
trait HasVerifiedScope
{
    /**
     * Scoping Model Queries to Verified / Un-Verified Records using `email_verified_at` attribute.
     *
     * @param Builder $query
     * @param bool $isVerified
     * @return Builder
     */
    public function scopeVerified(Builder $query, bool $isVerified = true): Builder
    {
        return $query->whereNull('email_verified_at', not: !$isVerified);
    }

    /**
     * Scoping Model Queries to Un-Verified Records using `email_verified_at` attribute.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeUnVerified(Builder $query): Builder
    {
        return $this->verified(isVerified: false);
    }

    /**
     * Check if Model is Verified
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return (bool)$this->getAttribute('email_verified_at');
    }
}
