<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
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
            'name' => [
                'ar' => fake('ar_SA')->name(),
                'en' => 'تصنيف جديد #' . fake()->numberBetween(1, 100000),
            ],
            'parent_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
            'is_active' => $this->faker->boolean(90)
        ];
    }
}
