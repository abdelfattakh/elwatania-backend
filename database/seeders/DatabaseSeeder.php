<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            CountrySeeder::Class,
            CitySeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            FavouriteSeeder::class,
            AreaSeeder::class,
            AddressSeeder::class,
            PaymentMethodSeeder::class,
            BannerSeeder::class,
//            OrderSeeder::class,
//            OrderItemSeeder::class,
        ]);
    }
}
