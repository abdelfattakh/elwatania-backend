<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Coupons;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'payment_method_id',
        'payment_method_name',
        'coupon_id',
        'coupon_code',
        'delivery_fees',
        'address_street_name',
        'name',
        'family_name',
        'address_phone',
        'address_country_name',
        'address_city_name',
        'address_building_no',
        'address_flat_no',
        'address_level',
        'address_area_name',
        'shipping_time',
        'tax_price',
        'total',
        'sub_total',
        'order_code'
    ];
    protected $casts = [
        'status' => OrderStatusEnum::class,
        'user_id' => 'int',
        'total' => 'float',
        'address_building_no' => 'int',
        'address_flat_no' => 'int',
        'address_level' => 'int',
        'payment_method_id' => 'int',
        'coupon_id' => 'int',
        'coupon_code' => 'int',
        'address_id' => 'int',
        'subtotal' => 'float',
        'tax_price' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->order_code='#'.self::generateCode();

        });
    }

    /**
     * @return string
     */
    public static function generateCode()
    {
        $code = Str::random(6);
        if (Order::where('order_code', $code)->exists()) {
            self::generateCode();
        }
        return $code;

    }

    /**
     *
     * @return HasMany
     */
    public
    function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public
    function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * @return BelongsTo
     */
    public
    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public
    function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }


    public
    function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
