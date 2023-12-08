<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
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
                // https://www.elarabygroup.com/ar/tornado-multi-function-boiler-4-liter-750-892-watt-champagne-x-white-ttm-9000
                'name' => [
                    'ar' => 'غلاية مياه تورنيدو متعددة الاستخدام 4 لتر ، 750-892 وات ، شامبين × أبيض TTM-9000',
                    'en' => 'TORNADO Multi-function Boiler 4 Liter, 750-892 Watt, Champagne x White TTM-9000',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 1319,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "غلاية مياه تورنيدو متعددة الاستخدام\nسعة الغلاية : 4 لتر\nالقدرة الكهربائية : 750-892 وات\nلون الغلاية : شامبين × أبيض\nبلد المنشأ : الصين\nضمان مجاني شامل لمدة سنة",
                    'en' => "TORNADO Multi-function Boiler\nBoiler Capacity : 4 Liter\nElectric Power : 750-892 Watt\nBoiler Color : Champagne x White\nCountry Of Origin : China\n1 Year Full Free Warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-multi-function-boiler-4-liter-750-892-watt-champagne-x-white-ttm-9000-ar.jpg',
            ],
            [
                // https://www.elarabygroup.com/ar/tornado-digital-tea-maker-1-7-liter-1850-2200-watt-stainless-x-black-ttm-800
                'name' => [
                    'ar' => 'ماكينة شاي ديجيتال تورنيدو 1.7 لتر ، 1850-2200 وات ، استانلس × أسود TTM-800',
                    'en' => 'TORNADO Digital Tea Maker 1.7 Liter, 1850-2200 Watt, Stainless x Black TTM-800',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 2122,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "ماكينة شاي ديجيتال تورنيدو\nسعة ماكينة الشاي : 1.7 لتر\nالقدرة الكهربائية : 1850-2200 وات\nلون ماكينة الشاي : استانلس × أسود\nبلد المنشأ : الصين\nضمان مجاني شامل لمدة سنة",
                    'en' => "TORNADO Digital Tea Maker\nTea Maker Capacity : 1.7 Liter\nElectric Power : 1850-2200 Watt\nTea Maker Color : Stainless x Black\nCountry Of Origin : China\n1 Year Full Free Warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-digital-tea-maker-1-7-liter-1850-2200watt-stainless-x-black-ttm-800-ar.jpg',
            ],
            [
                // https://www.elarabygroup.com/en/tornado-pancake-maker-700-watt-batter-mixing-bowl-blue-tpc-700
                'name' => [
                    'ar' => 'صانع البان كيك تورنيدو 700 وات ، وعاء للخلط ، أزرق TPC-700',
                    'en' => 'TORNADO Pancake Maker 700 Watt, Batter Mixing Bowl, Blue TPC-700',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 799,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "صانع البان كيك تورنيدو\nالقدرة الكهربائية : 700 وات\nاللون : أزرق\nمزود بوعاء للخلط\nبلد المنشأ : الصين\nضمان مجاني شامل لمدة سنة",
                    'en' => "TORNADO Pancake Maker\nElectric Power : 700 Watt\nColor : Blue\nWith Batter Mixing Bowl\nCountry Of Origin : China\n1 Year Full Free Warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-pancake-maker-700-watt-batter-mixing-bowl-blue-tpc-700-en_1.jpg',
            ],
            [
                // https://www.elarabygroup.com/en/tornado-yoghurt-maker-18-watt-7-glass-cups-white-x-stainless-tym-18
                'name' => [
                    'ar' => 'ماكينة زبادي تورنيدو 18 وات ، 7 أكواب زجاج ، أبيض × استانلس TYM-18',
                    'en' => 'TORNADO Yoghurt Maker 18 Watt, 7 Glass Cups, White x Stainless TYM-18',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 799,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "ماكينة زبادي تورنيدو لون أبيض × استانلس\nالقدرة الكهربائية : 18 وات\nعدد الأكواب : 7 أكواب زجاج\nمزودة بشاشة LCD لضبط الوقت\nبلد المنشأ : الصين\nضمان مجاني شامل لمدة سنة",
                    'en' => "TORNADO Yoghurt Maker In White x Stainless Color\nElectric Power : 18 Watt\nNumber of Cups : 7 Glass Cups\nWith LCD Display\nCountry Of Origin : China\n1 Year Full Free Warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-yoghurt-maker-18watt-7-glass-cups-white-x-stainless-tym-18-ar.jpg',
            ],
            [
                // https://www.elarabygroup.com/en/tornado-stainless-steel-temperature-control-kettle-1-7-liter-1850-2200-watt-stainless-x-black-tkse-4071
                'name' => [
                    'ar' => 'غلاية مياه إليكترونية تورنيدو استانلس ستيل 1.7 لتر ، 1850-2200 وات ، استانلس × أسود TKSE-4071',
                    'en' => 'TORNADO Stainless Steel Temperature Control Kettle 1.7 Liter, 1850-2200 Watt, Stainless x Black TKSE-4071',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 1265,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "غلاية مياه إليكترونية تورنيدو استانلس ستيل\nسعة الغلاية : 1.7 لتر\nالقدرة الكهربائية : 1850-2200 وات\nلون الغلاية : استانلس × أسود\nبلد المنشأ : الصين\nضمان مجاني شامل لمدة سنة",
                    'en' => "TORNADO Stainless Steel Temperature Control Kettle\nKettle Capacity : 1.7 Liter\nElectric Power : 1850-2200 Watt\nKettle Color : Stainless x Black\nCountry Of Origin : China\n1 Year Full Free Warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-temperature-control-kettle-1-7l-1850-2200watt-stainless-x-black-tkse-4071-en.jpg',
            ],
            [
                // https://www.elarabygroup.com/en/tornado-ceiling-fan-56-inch-3-metal-blades-5-speeds-white-color-tcf56ww
                'name' => [
                    'ar' => 'مروحة سقف تورنيدو 56 بوصة ، 3 ريشة ، أبيض TCF56WW',
                    'en' => 'TORNADO Ceiling Fan 56 Inch, 3 Blades, White TCF56WW',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 850,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "مروحة سقف تورنيدو 56 بوصة لون أبيض\nتعمل بدون ريموت كنترول\nمزودة بـ 3 ريشة معدن\nتعمل بـ 5 سرعات اختيارية لاندفاع هواء المروحة\nبلد المنشأ : مصر\n5 سنوات ضمان مجاني شامل",
                    'en' => "TORNADO Ceiling Fan 56 Inch With White Color\nWorking Without Remote Control\n3 Metal Blades\nWorking With 5 Selectable Speeds\nCountry Of Origin : Egypt\n5 Years Full Free Warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-ceiling-fan-56-inch-with-3-metal-blades-and-5-speeds-white-color-tcf56ww.jpg',
            ],
            [
                // https://www.elarabygroup.com/ar/tornado-toaster-2-slices-850-watt-black-color-tt-852-b
                'name' => [
                    'ar' => 'توستر تورنيدو 2 شريحة ، 850 وات ، أسود TT-852-B',
                    'en' => 'TORNADO Toaster 2 Slices , 850 Watt, Black TT-852-B',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 740,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "توستر تورنيدو لون أسود\nالقدرة الكهربائية : 850 وات\nعدد الشرائح : 2 شريحة\nإمكانية التحكم في درجة التحميص المطلوبة\nبلد المنشأ : الصين\nضمان مجاني شامل لمدة سنة",
                    'en' => "TORNADO Toaster in Black Color\nElectric Power : 850 Watt\nNumber of Slices : 2 Slices\nAdjustable Browning Control\nCountry Of Origin : China\n1 Year Full Free Warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-toaster-2-slices-850-watt-in-black-color-tt-852-b.jpg',
            ],
            [
                // https://www.elarabygroup.com/en/hisense-split-air-conditioner-1-5-hp-cool-heat-inverter-digital-white-hi-e12invhp
                'name' => [
                    'ar' => 'تكييف هايسنس اسبليت 1.5 حصان بارد - ساخن انفرتر ديچيتال ، أبيض HI-E12INVHP',
                    'en' => 'HISENSE Split Air Conditioner 1.5 HP Cool - Heat Inverter Digital, White HI-E12INVHP',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 14960,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "تكييف هايسنس 1.5 حصان بارد - ساخن انفرتر ديچيتال\nسعة تبريد التكييف : 11600 ( و ح ب / ساعة )\nسعة تدفئة التكييف : 12966 ( و ح ب / ساعة )\nلون التكييف : أبيض\nمزود بتكنولوجيا الانفرتر و مولد أيونات\nبلد المنشأ : الأردن\n5 سنوات ضمان مجاني شامل",
                    'en' => "HISENSE Air Conditioner 1.5 HP cool - heat Inverter Digital\nCooling Capacity : 11600 ( BTU/H )\nHeating Capacity : 12966 ( BTU/H )\nAir conditioner Color : white\nWith Inverter and Anion generator technology\nCountry of origin : Jordan\n5 Years full free warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/h/i/hisense-split-air-conditioner-1-5-hp-cool-heat-inverter-digital-white-hi-e12invhp-open.jpg',
                'images' => [
                    'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/h/i/hisense-split-air-conditioner-1-5-hp-cool-heat-inverter-digital-white-hi-e12invhp-side.jpg',
                    'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/h/i/hisense-split-air-conditioner-1-5-hp-cool-heat-inverter-digital-hi-e12invhp-remote_1_.jpg',
                    'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/h/i/hisense-split-air-conditioner-1-5-hp-cool-heat-inverter-digital-hi-e12invhp-closed.jpg',
                ],
            ],
            [
                // https://www.elarabygroup.com/en/tornado-ceiling-fan-60-inch-3-blades-white-tcfm60
                'name' => [
                    'ar' => 'مروحة سقف تورنيدو 60 بوصة ، 3 ريشة ، أبيض TCFM60',
                    'en' => 'TORNADO Ceiling Fan 60 Inch, 3 Blades, White TCFM60',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 1840,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "مروحة سقف تورنيدو 60 بوصة لون أبيض\nتعمل بدون ريموت كنترول\nمزودة بـ 3 ريشة معدن\nتعمل بـ 5 سرعات اختيارية لاندفاع هواء المروحة\nبلد المنشأ : ماليزيا\n5 سنوات ضمان مجاني شامل",
                    'en' => "TORNADO ceiling fan 60 inch with white color\nWorking without remote control\n3 Metal blades\nWorking with 5 selectable speeds\nCountry of origin : Malaysia\n5 Years full free warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-ceiling-fan-60-inch-3-blades-white-tcfm60.jpg',
                'images' => [
                    'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-ceiling-fan-60-inch-3-blades-white-tcfm60-switch-_key.jpg',
                ],
            ],
            [
                // https://www.elarabygroup.com/en/tornado-wall-fan-18-inch-4-plastic-blades-3-speeds-black-color-twf-18
                'name' => [
                    'ar' => 'مروحة حائط تورنيدو 18 بوصة ، 4 ريشة ، أسود TWF-18',
                    'en' => 'TORNADO Wall Fan 18 Inch, 4 Blades, Black TWF-18',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 1840,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "مروحة سقف تورنيدو 60 بوصة لون أبيض\nتعمل بدون ريموت كنترول\nمزودة بـ 3 ريشة معدن\nتعمل بـ 5 سرعات اختيارية لاندفاع هواء المروحة\nبلد المنشأ : ماليزيا\n5 سنوات ضمان مجاني شامل",
                    'en' => "TORNADO Wall Fan 18 Inch With Black Color\nWorking Without Remote Control\n4 Plastic Blades\nWorking With 3 Selectable Speeds\nCountry Of Origin : Egypt\n5 Years Full Free Warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-wall-fan-18-inch-with-4-plastic-blades-and-3-speeds-in-black-color-twf-18.jpg',
            ],
            [
                // https://www.elarabygroup.com/en/tornado-32-inch-led-tv-hd-built-in-receiver-2-hdmi-2-usb-inputs-32er9500e
                'name' => [
                    'ar' => 'مروحة سقف تورنيدو 60 بوصة ، 3 ريشة ، أبيض TCFM60',
                    'en' => 'TORNADO HD TV 32 Inch, Built-In Receiver 32ER9500E',
                ],
                'category_id' => Category::query()->inRandomOrder()->first()?->getKey() ?? null,
                'brand_id' => Brand::query()->inRandomOrder()->first()?->getKey() ?? null,
                'price' => 1840,
                'discount_value' => 0,
                'discount_expiration_date' => null,
                'shipping_time' => [
                    'ar' => 'الشحن خلال 3 إلى 7 أيام',
                    'en' => 'Shipping from 3 to 7 days',
                ],
                'general_description' => [
                    'ar' => "تليفزيون تورنيدو إل إى دى 32 بوصة اتش دي\nمزود بريسيفر داخلي\nمدخلين اتش دى ام اى\nمزود بمدخلين فلاشة\nبلد المنشأ : مصر\n3 سنوات ضمان مجاني شامل",
                    'en' => "TORNADO LED TV 32 Inch HD\nWith Built-In Receiver\n2 HDMI Inputs\n2 USB Inputs\nCountry of Origin : Egypt\n3 Years Full Free Warranty",
                ],
                'technical_description' => null,
                'is_active' => true,
                'is_exclusive' => true,
                'image' => 'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-32-inch-led-tv-hd-built-in-receiver-2-hdmi-2-usb-inputs-32er9500e-insurance.jpg',
                'images' => [
                    'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-led-tv-32-inch-hd-with-built-in-receiver-2-hdmi-and-2-usb-inputs-32er9500e-side-left-zoom_1.jpg',
                    'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-led-tv-32-inch-hd-with-built-in-receiver-2-hdmi-and-2-usb-inputs-32er9500e-front-zoom.jpg',
                    'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-led-tv-32-inch-hd-with-built-in-receiver-2-hdmi-and-2-usb-inputs-32er9500e-side-zoom.jpg',
                    'https://www.elarabygroup.com/media/catalog/product/cache/b2a75c2f978d2066af0275388b9e495d/t/o/tornado-led-tv-32-inch-hd-with-built-in-receiver-2-hdmi-and-2-usb-inputs-32er9500e-side-left-zoom.jpg',
                ],
            ],
        ])->each(function (array $el) {
            /** @var Product $product */
            $product = Product::query()->create(Arr::except($el, keys: ['image', 'images']));
//            try {
//                $product->addMediaFromUrl($el['image'])->toMediaCollection(Product::$coverMediaCollection);
//                collect($el['images'] ?? [])->each(fn($img) => $product->addMediaFromUrl($img)->toMediaCollection(Product::$mediaCollection));
//            } catch (Exception) {
//                // nothing.
//            }
        });

        Product::factory(200)->create();
    }
}
