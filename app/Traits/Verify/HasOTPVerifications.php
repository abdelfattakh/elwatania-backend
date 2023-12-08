<?php

namespace App\Traits\Verify;

use App\Models\OTP;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Nette\NotImplementedException;

trait HasOTPVerifications
{
    /**
     * Check if this User Has Verification Code for username and type.
     *
     * @param string $username
     * @param string $code
     * @param string|null $type
     * @return OTP|null
     */
    public function getVerificationCode(string $username, string $code, ?string $type = null): OTP|null
    {
        /** @var OTP $otp */
        $otp = $this->otps()
            ->when(filled($type), fn ($q) => $q->where('type', $type))
            ->where('target', $username)
            ->where('code', $code)
            ->valid()
            ->first();

        $otp?->markAsUsed();

        return $otp;
    }

    /**
     * OTPs Relation.
     *
     * @return MorphMany
     */
    public function otps(): MorphMany
    {
        return $this->morphMany(OTP::class, 'model');
    }

    /**
     * Updates the associated user.
     *
     * @param OTP $otp
     * @return ?string
     */
    public function applyOTPAction(OTP $otp): ?string
    {
        return match ($otp->getAttribute('type')) {
            'new_email' => $this->onNewEmailOTPVerified(email: $otp->target),
            'verify_email' => $this->onEmailOTPVerified(),
            'new_phone' => $this->onNewPhoneOTPVerified(phone: in_array(CanChangeInternationalPhone::class, class_uses(self::class), true) ? phone($otp->target) : $otp->target),
            'verify_phone' => $this->onPhoneOTPVerified(),
            'reset_phone', 'reset_email' => $this->onResetPasswordVerified(username: $otp->target),
            default => throw new NotImplementedException(),
        };
    }

    /**
     * Create New OTP for username with type
     *
     * @param string $username
     * @param string|null $type
     * @return OTP|Model
     */
    public function newOTP(string $username, ?string $type = null): OTP|Model
    {
        return $this->otps()->create([
            'type' => $type,
            'code' => rand(100000, 999999),
            'target' => $username,
            'expires_at' => $this->freshTimestamp()->addMinutes(10),
            'used_at' => null,
        ]);
    }
}
