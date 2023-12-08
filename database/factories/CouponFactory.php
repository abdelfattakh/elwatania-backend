<?php

namespace Database\Factories;

use App\Enums\CouponTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);
        $this->faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($this->faker));
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => $this->faker->promotionCode,
            'type' => $this->faker->randomElement([CouponTypeEnum::FIXED()->value, CouponTypeEnum::PERCENTAGE()->value]),
            'no_uses' => rand(1, 50),
            'value' => number_format(rand(1, 20), 2, '.', ''),
            'starts_at' => now(),
            'ends_at' => Carbon::now()->addDays(rand(1, 10)),
        ];
    }
}
