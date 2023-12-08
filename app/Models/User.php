<?php

namespace App\Models;

use App\Contracts\Verify\HasOTPVerifications;
use App\Traits\Scopes\HasVerifiedScope;
use App\Traits\Verify\CanChangeEmail;
use App\Traits\Verify\CanResetPasswordOTP;
use App\Traits\Verify\HasOTPVerifications as OTPVerifyTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Maize\Markable\Markable;
use ProtoneMedia\LaravelVerifyNewEmail\MustVerifyNewEmail;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia, HasOTPVerifications
{
    use HasApiTokens,
        HasFactory,
        HasVerifiedScope,
        InteractsWithMedia,
        Markable,
        MustVerifyNewEmail,
        Notifiable,
        OTPVerifyTrait,
        CanResetPasswordOTP,
        CanChangeEmail {
        MustVerifyNewEmail::newEmail insteadof CanChangeEmail;
        CanChangeEmail::newEmail as newEmailOTP;
    }

    /**
     * Primary Media Collection
     */
    public static string $mediaCollection = 'users-images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * User has Many Addresses.
     *
     * @return HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * User Full Name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->getAttribute('first_name') . ' ' . $this->getAttribute('last_name');
    }

    /**
     * User has Many Reviews.
     *
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * User Has Many Favs.
     *
     * @return HasMany
     */
    public function favourites(): HasMany
    {
        return $this->hasMany(Favourite::class);
    }

    /**
     * User has Many Orders.
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Defining Media Collections for Category Images.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::$mediaCollection)
            ->acceptsMimeTypes(mimeTypes: ['image/jpeg', 'image/png'])
            ->withResponsiveImages()
            ->singleFile();
    }

    /**
     * Overriding the default Laravel Email Verification.
     * The default Laravel implementation requires the user to be logged in before it can verify its email address.
     * using `protonemedia/laravel-verify-new-email` logic to handle that first verification flow as well.
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->newEmail($this->getEmailForVerification());
    }

    /**
     * Main Image Morph Relation
     *
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', 'user_image');
    }

    /**
     * User has many social accounts.
     *
     * @return MorphMany
     */
    public function socials(): MorphMany
    {
        return $this->morphMany(Social::class, 'sociable');
    }

    /**
     * User has many Cart Objs use DatabaseStorageModel.
     *
     * @return HasMany
     */
    public function carts(): HasMany
    {
        return $this->hasMany(DatabaseStorageModel::class);
    }
}
