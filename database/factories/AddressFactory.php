<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        /** @var Country $country */
        $country = Country::query()->whereHas('cities')->inRandomOrder()->first();
        /** @var City $city */
        $city = $country->cities()->whereHas('areas')->inRandomOrder()->first();
        /** @var Area $area */
        $area = $city->areas()->inRandomOrder()->first();

        return [
            'street_name' => $this->faker->streetName,
            'phone' => $this->faker->numerify('010########'),
            'country_id' => $country?->getKey(),
            'city_id' => $city?->getKey(),
            'area_id' => $area?->getKey(),
            'name' => $this->faker->name,
            'family_name' => $this->faker->name,
            'building_no' => rand(1, 20),
            'level' => rand(1, 7),
            'flat_no' => rand(20, 30),
            'user_id' => User::inRandomOrder()->first()->id
        ];
    }
}
