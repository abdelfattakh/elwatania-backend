<?php

namespace App\Models;

use App\Traits\Scopes\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class PaymentMethod extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasActiveScope;

    protected $fillable = [
        'name',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',

    ];

    public $translatable = [
        'name',
    ];

    /**
     * Get all of the orders for the PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Comment::class, 'foreign_key', 'local_key');
    }
}
