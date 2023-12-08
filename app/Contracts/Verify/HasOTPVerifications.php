<?php

namespace App\Contracts\Verify;

use App\Models\OTP;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasOTPVerifications
{
    /**
     * Check if this User Has Verification Code for username and type.
     *
     * @param string $username
     * @param string $code
     * @param string|null $type
     * @return OTP|null
     */
    public function getVerificationCode(string $username, string $code, ?string $type = null): OTP|null;

    /**
     * Updates the associated user.
     *
     * @param OTP $otp
     * @return ?string
     */
    public function applyOTPAction(OTP $otp): ?string;

    /**
     *  OTPs Relation.
     *
     * @return MorphMany
     */
    public function otps(): MorphMany;

    /**
     * Create New OTP for username with type
     *
     * @param string $username
     * @param string|null $type
     * @return OTP|Model
     */
    public function newOTP(string $username, ?string $type = null): OTP|Model;
}
