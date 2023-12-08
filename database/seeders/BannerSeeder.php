<?php

namespace Database\Seeders;

use App\Models\BannerModel;
use App\Models\Brand;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class BannerSeeder extends Seeder
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
                'name' => 'Jan Offers',
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/offers/1673871188755.jpg',
            ],
            [
                'name' => 'La Garemina',
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/offers/1671110590466.jpg',
            ],
            [
                'name' => 'Sharp',
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/offers/1672314692820.jpg',
            ],
            [
                'name' => 'Tiger',
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/offers/1671110252953.jpg',
            ],
        ])->each(function (array $el) {
            /** @var BannerModel $banner */
            $banner = BannerModel::query()->create(Arr::except($el, 'image'));
            try {
                $banner->addMediaFromUrl($el['image'])->toMediaCollection('banner_images');
            } catch (Exception) {
                // nothing.
            }
        });
    }
}
