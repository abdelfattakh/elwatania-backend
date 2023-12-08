<?php

namespace Database\Seeders;

use App\Models\Brand;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => [
                    'ar' => 'توريندو',
                    'en' => 'Tornado',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1603749486605.png',
            ],
            [
                'name' => [
                    'ar' => 'توشيبا',
                    'en' => 'Toshiba',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1639403464367.png',
            ],
            [
                'name' => [
                    'ar' => 'شارب',
                    'en' => 'Sharp',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1603749085705.png',
            ],
            [
                'name' => [
                    'ar' => 'هوفر',
                    'en' => 'Hoover',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1603809463289.png',
            ],
            [
                'name' => [
                    'ar' => 'لا جارمينيا',
                    'en' => 'La Germania',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/161990016555.png',
            ],
            [
                'name' => [
                    'ar' => 'سوني',
                    'en' => 'Sony',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1603809304203.png',
            ],
            [
                'name' => [
                    'ar' => 'كاندي',
                    'en' => 'Candy',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/160380909874.png',
            ],
            [
                'name' => [
                    'ar' => 'تايجر',
                    'en' => 'Tiger',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1603808801555.png',
            ],
            [
                'name' => [
                    'ar' => 'هيتاشي',
                    'en' => 'Hitachi',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1603810943870.png',
            ],
            [
                'name' => [
                    'ar' => 'ني اي سي',
                    'en' => 'NEC',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/160380961512.png',
            ],
            [
                'name' => [
                    'ar' => 'سيكو',
                    'en' => 'Seiko',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1603809801784.png',
            ],
            [
                'name' => [
                    'ar' => 'جراند سيكو',
                    'en' => 'Grand Seiko',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1614032381250.png',
            ],
            [
                'name' => [
                    'ar' => 'البا',
                    'en' => 'Alba',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1614032381250.png',
            ],
            [
                'name' => [
                    'ar' => 'هيلر',
                    'en' => 'Heller',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1603808570420.png',
            ],
            [
                'name' => [
                    'ar' => 'دينا بوك',
                    'en' => 'DynaBook',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1605313667970.png',
            ],
            [
                'name' => [
                    'ar' => 'هاما',
                    'en' => 'Hama',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1605313667970.png',
            ],
            [
                'name' => [
                    'ar' => 'هاي سينس',
                    'en' => 'HiSense',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/1646253623204.png',
            ],
            [
                'name' => [
                    'ar' => 'هاي وان بلس',
                    'en' => 'HiOne+',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/brands/166437252983.png',
            ],
        ])->each(function (array $el) {
            /** @var Brand $brand */
            $brand = Brand::query()->create(Arr::except($el, 'image'));
            try {
                $brand->addMediaFromUrl($el['image'])->toMediaCollection('brand_image');
            } catch (Exception) {
                // nothing.
            }
        });
    }
}
