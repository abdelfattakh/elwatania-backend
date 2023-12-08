<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id,
            'product_id' => Product::inRandomOrder()->first()->id,
            'product_name' => $this->faker->name,
            'quantity' => rand(100, 500),
            'product_general_description' => $this->faker->text,
            'product_technical_description' => $this->faker->text,
            'product_price' => number_format(rand(1, 20), 2, '.', ''),
        ];
    }
}
