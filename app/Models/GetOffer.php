<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GetOffer extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table='get_offers';

    protected $fillable = [
        'email',
    ];
}
