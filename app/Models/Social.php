<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Social extends Model
{
    use HasFactory;
    protected $table = 'social';

    protected $fillable = [
        'sociable_id',
        'sociable_type',
        'provider',
        'provider_user_id'
    ];

    protected $hidden = [
        'sociable_type',
        'sociable_id',
        'provider_user_id',
        'created_at',
        'updated_at'
    ];

    public function sociable(): MorphTo
    {
        return $this->morphTo();
    }

}
