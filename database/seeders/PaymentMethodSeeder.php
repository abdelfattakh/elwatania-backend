<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            ['name' => json_encode(["ar" => "كاش", "en" => "cash"]), 'is_active' => 1],
            ['name' => json_encode(["ar" => "فيزا", "en" => "credit card"]), 'is_active' => 1]
        ];
        PaymentMethod::Insert($paymentMethods);
    }
}
