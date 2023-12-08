<?php

namespace App\Models;

use App\Traits\Scopes\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasActiveScope;


    protected $fillable = [
        'name',
        'is_active',
        'delivery_fees',
        'country_id'
    ];
    protected $casts=[
        'country_id'=>'int',
        'is_active'=>'boolean',
        'delivery_fees'=>'int'
    ];
    public $translatable = ['name'];

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return HasMany
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

}
