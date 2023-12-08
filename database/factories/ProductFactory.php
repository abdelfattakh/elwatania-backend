<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
                'ar' => 'منتج جديد #' . fake()->numberBetween(1, 100000),
                'en' => $this->faker->productName,
            ],
            'category_id' => Category::inRandomOrder()->where('parent_id','!=',null)->first()->id,
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'price' => number_format(rand(1, 20), 2, '.', ''),
            'discount_value' => rand(5, 100),
            'discount_expiration_date' => Carbon::now()->addDays(3),
            'is_active' => $this->faker->boolean(),
            'is_exclusive' => $this->faker->boolean(),
            'general_description' => $this->faker->text,
            'technical_description' => $this->faker->text,
            'shipping_time' => $this->faker->realText(50),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
//    public function configure(): static
//    {
//        return $this->afterMaking(function (Product $product) {
//            //
//        })->afterCreating(function (Product $product) {
//            $product->addMediaFromUrl('https://loremflickr.com/320/240/electornic/all')->toMediaCollection(Product::$mediaCollection);
//            $product->addMediaFromUrl('https://loremflickr.com/320/240/electornic/all')->toMediaCollection(Product::$mediaCollection);
//            $product->addMediaFromUrl('https://loremflickr.com/320/240/electornic/all')->toMediaCollection(Product::$coverMediaCollection);
//        });
//    }
}
