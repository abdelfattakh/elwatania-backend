<?php

namespace App\Models;

use App\Traits\Scopes\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Area extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasActiveScope;
    use HasTranslations;

    protected $fillable = [
        'name',
        'city_id',
        'delivery_fees',
        'is_active',
        'shipping_time',


    ];
    public $translatable = ['name','shipping_time'];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'city_id' => 'int',
        'delivery_fees' => 'int',
    ];


    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
