<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Models\Address;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $country = Country::query()->whereHas('cities')->inRandomOrder()->first();
        /** @var City $city */
        $city = $country->cities()->whereHas('areas')->inRandomOrder()->first();
        /** @var Area $area */
        $area = $city->areas()->inRandomOrder()->first();


        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'address_id' => Address::inRandomOrder()->first()->id,
//            'payment_method_id' => rand(1,3),
            'payment_method_name' => $this->faker->randomElement([PaymentMethodEnum::AUTO()->value, PaymentMethodEnum::LATER()->value, PaymentMethodEnum::MANUEL()]),
            'coupon_id' => Coupon::inRandomOrder()->first()->id,
            'coupon_code' => substr(md5(mt_rand()), 0, 7),
            'delivery_fees' => rand(10, 100),
            'tax_price' => rand(5, 50),
            'status' => $this->faker->randomElement(OrderStatusEnum::toArray()),
            'address_street_name' => $this->faker->text,
            'address_phone' => $this->faker->numerify('010########'),
            'address_country_name' => $country->name,
            'address_city_name' => $city->name,
            'address_area_name' => $this->faker->text,
            'order_code' => random_int(1000000, 2000000),
            'total' => rand(100, 50),
            'sub_total' => rand(50, 100),
        ];
    }
}
