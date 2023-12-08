<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stars',
        'reviewable_id',
        'reviewable_type',
        'comment',
        'user_name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->user_name ??= User::find($model->user_id)?->first_name ?? '';
        });
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewable()
    {
        return $this->morphTo();
    }

    /**
     * @param Builder $query
     * @param int $stars
     * @return Builder
     */

    public function scopeStars(Builder $query, int $stars): Builder
    {
        return $query->when(filled($stars), fn($q) => $q->where('stars', $stars));
    }
}
