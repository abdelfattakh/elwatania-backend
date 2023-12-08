<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => [
                'ar' => fake('ar_SA')->name(),
                'en' => fake('en_US')->name(),
            ],
            'city_id' => City::inRandomOrder()->first()->id,
            'is_active' => $this->faker->boolean(90),
            'shipping_time' => [
                'ar' => 'الشحن من 5 ل6 ايام',
                'en' => 'shipping will be from 5 to 6 days',
            ],
        ];
    }
}
