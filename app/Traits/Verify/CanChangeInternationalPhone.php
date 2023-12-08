<?php

namespace App\Traits\Verify;

use App\Notifications\VerifyWithOTP;
use Illuminate\Auth\Events\Verified;
use Illuminate\Database\Eloquent\Builder;
use Propaganistas\LaravelPhone\PhoneNumber;

trait CanChangeInternationalPhone
{
    /**
     * Boot Of CanChangeInternationalPhone
     * @return void
     */
    public static function bootCanChangeInternationalPhone(): void
    {
        static::saving(function (self $model) {
            if ($model->isDirty('phone') && $model->phone) {
                $model->phone_normalized = preg_replace('[^0-9]', '', $model->phone);
                $model->phone_national = preg_replace('[^0-9]', '', phone($model->phone, $model->phone_country)->formatNational());
                $model->phone_e164 = phone($model->phone, $model->phone_country)->formatE164();
            }
        });
    }

    /**
     * User Change Change His Phone.
     *
     * @param PhoneNumber $phone
     * @param bool $markAsVerified
     * @return void
     */
    public function newPhone(PhoneNumber $phone, bool $markAsVerified = false): void
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
        if (!filled($this->phone) || !filled($this->phone_country)) {
            return null;
        }

        return phone($this->phone, $this->phone_country)->formatE164();
    }

    /**
     * On New Phone OTP Verified.
     *
     * @param PhoneNumber $phone
     * @return ?string
     */
    public function onNewPhoneOTPVerified(PhoneNumber $phone): ?string
    {
        $this->update(['phone' => $phone, 'phone_country' => $phone->getCountry()]);
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
     * @param ?PhoneNumber $pendingUserPhone
     * @param string $type
     * @return void
     */
    public function sendPendingPhoneVerificationNotification(?PhoneNumber $pendingUserPhone, string $type = 'new_phone'): void
    {
        if (!filled($pendingUserPhone)) {
            return;
        }
        $otp = $this->newOTP(username: $pendingUserPhone->formatE164(), type: $type);
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
        return $query->where(function ($q) use ($search) {
            $q->where('phone_normalized', 'LIKE', preg_replace('[^0-9]', '', $search) . '%')
                ->orWhere('phone_national', 'LIKE', preg_replace('[^0-9]', '', $search) . '%')
                ->orWhere('phone_e164', 'LIKE', preg_replace('[^+0-9]', '', $search) . '%');
        });
    }

    /**
     * Scope of Has Phone Number.
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeHasPhone(Builder $query, string $search): Builder
    {
        return $query->where('phone_e164', $search);
    }
}
