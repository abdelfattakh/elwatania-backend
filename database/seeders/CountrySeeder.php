<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ['name' => json_encode(["ar" => "مصر", "en" => "egypt"]), 'is_active' => 1],
            ['name' => json_encode(["ar" => "المملكه العربيه  السعوديه", "en" => "Saudi Arabia"]), 'is_active' => 1],
            ['name' => json_encode(["ar" => "الولايات المتحده", "en" => "Usa"]), 'is_active' => 1],
        ];
        Country::Insert($countries);
    }
}
