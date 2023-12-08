<?php

namespace Database\Seeders;

use App\Models\Category;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        collect([
            [
                'name' => [
                    'ar' => 'تليفزيونات والكترونيات',
                    'en' => 'TVs and Electronics',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/category/tvs-and-electronics.png',
            ],
            [
                'name' => [
                    'ar' => 'تكييفات و منقيات الهواء',
                    'en' => 'Air Conditioners and Purifiers',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/category/air-conditioners-coolers-and-purifiers.png',
            ],
            [
                'name' => [
                    'ar' => 'مراوح',
                    'en' => 'Fans',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/category/fans.png',
            ],
            [
                'name' => [
                    'ar' => 'أجهزة منزلية كبيرة',
                    'en' => 'Large Home Appliances',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/category/large-home-appliances.png',
            ],
            [
                'name' => [
                    'ar' => 'محضرة القهوة',
                    'en' => 'Coffee maker',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/category/coffee-maker.png',
            ],
            [
                'name' => [
                    'ar' => 'بوتاجازات فري ستاند',
                    'en' => 'Freestanding Cookers',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/category/freestanding-cookers.png',
            ],
            [
                'name' => [
                    'ar' => 'شفاطات',
                    'en' => 'Ventilating Fans',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/category/ventilating-fans.png',
            ],
            [
                'name' => [
                    'ar' => 'قطع غيار استهلاكية',
                    'en' => 'Consumables Spare Parts',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/category/consumables-spare-parts.png',
            ],
            [
                'name' => [
                    'ar' => 'أجهزة تحضير الطعام',
                    'en' => 'Food Preparation Appliances',
                ],
                'is_active' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/category/food-preparation-appliances.png',
            ],
        ])->each(function (array $el) {
            /** @var Category $category */
            $category = Category::query()->create(Arr::except($el, 'image'));
            try {
                $category->addMediaFromUrl($el['image'])->toMediaCollection('category_image');
            } catch (Exception) {
                // nothing.
            }
        });

        Category::factory(10)->create();
    }
}
