<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'address_id',
        'product_id',
        'product_name',
        'quantity',
        'product_price',

    ];

    protected $casts = [
       'order_id'=>'int',
        'product_id'=>'int',
        'quantity'=>'int',
        'product_price'=>'int'
    ];


    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
