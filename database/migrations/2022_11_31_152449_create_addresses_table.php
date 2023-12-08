<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family_name');
            $table->foreignId('area_id')->constrained()->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->string('street_name');
            $table->string('building_no');
            $table->string('level');
            $table->string('flat_no');
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
        Schema::dropIfExists('addresses');
    }
};
