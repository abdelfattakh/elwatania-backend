<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->string('payment_method_name')->nullable();
            $table->string('order_code')->nullable()->unique();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->bigInteger('delivery_fees')->default(0);
            $table->bigInteger('tax_price')->default(0);
            $table->string('status');
            $table->string('address_street_name')->nullable();
            $table->string('address_phone')->nullable();
            $table->string('address_country_name')->nullable();
            $table->string('product_shipping_time')->nullable();
            $table->string('address_city_name')->nullable();
            $table->string('address_area_name')->nullable();
            $table->bigInteger('address_building_no')->nullable();
            $table->bigInteger('address_flat_no')->nullable();
            $table->bigInteger('address_level')->nullable();
            $table->double('total');
            $table->double('sub_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
