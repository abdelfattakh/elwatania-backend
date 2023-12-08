<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @method static inValid()
 * @method static valid()
 * @method static forModel()
 */
class OTP extends Model
{
    use HasFactory;
    use Prunable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'model_type',
        'model_id',
        'type',
        'code',
        'target',
        'expires_at',
        'used_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Model Related to this OTP.
     *
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     *  Check if the OTP is invalid.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInValid(Builder $query): Builder
    {
        return $query->whereNot(fn ($q) => $q->valid());
    }

    /**
     *  Check if the OTP is invalid.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query
            ->whereNull('used_at')
            ->where('expires_at', '>', now());
    }

    /**
     * Filter by the model.
     *
     * @param Builder $query
     * @param Model $model
     * @return Builder
     */
    public function scopeForModel(Builder $query, Model $model): Builder
    {
        return $query->where([
            $this->qualifyColumn('model_type') => get_class($model),
            $this->qualifyColumn('model_id') => $model->getKey(),
        ]);
    }

    /**
     * Check if Current OTP is Valid
     *
     * @return bool
     */
    public function isInValid(): bool
    {
        return !$this->isValid();
    }

    /**
     * Check if Current OTP is Valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return is_null($this->getAttribute('used_at')) && now()->isAfter($this->getAttribute('expires_at'));
    }

    /**
     * Check if Current OTP is Sent to Email
     *
     * @return bool
     */
    public function isSentToMail(): bool
    {
        return is_email($this->getAttribute('target'));
    }

    /**
     * Get the prunable model query.
     *
     * @return Builder
     */
    public function prunable(): Builder
    {
        return static::inValid();
    }

    /**
     * Mark OTP As Used.
     *
     * @return bool
     */
    public function markAsUsed(): bool
    {
        return $this->forceFill([
            'used_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Mark OTP As Used.
     *
     * @return bool
     */
    public function markAsUnUsed(): bool
    {
        return $this->forceFill([
            'used_at' => null,
        ])->save();
    }
}
