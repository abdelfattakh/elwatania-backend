<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'no_uses',
        'value',
        'starts_at',
        'ends_at',
    ];
    protected $casts=[
        'no_uses'=>'int',
        'value'=>'int',
        'starts_at'=>'datetime',
        'ends_at'=>'datetime'
    ];
    /**
     *  Check if the Coupon is valid.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query
            ->where('no_uses', '>', 0)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }
}
