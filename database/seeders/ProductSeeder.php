<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categorySpecs = [
            'smartphones' => [
                'display' => '6.5" OLED, 120 Гц',
                'ram' => '8 ГБ LPDDR5X',
                'storage' => '256 ГБ',
                'battery' => '4000 мАг',
            ],
            'laptops' => [
                'processor' => 'Intel Core i7',
                'gpu' => 'Iris Xe',
                'ram' => '16 ГБ',
                'storage' => '1 ТБ SSD',
            ],
            'gaming-laptops' => [
                'cooling' => 'Рідинне охолодження',
                'display' => '240 Гц',
                'power' => '300 Вт',
            ],
            'tvs' => [
                'resolution' => '4K UHD',
                'panel' => 'OLED',
                'refresh_rate' => '120 Гц',
            ],
            'headphones' => [
                'type' => 'Over-ear, wireless',
                'battery' => '30 год',
                'connection' => 'Bluetooth 5.2',
            ],
            'vacuums' => [
                'type' => 'Stick',
                'power' => '200 Вт',
                'capacity' => '0.8 л',
            ],
            'coffee-machines' => [
                'machine_type' => 'Automatic espresso',
                'pressure' => '15 бар',
                'beans_or_capsules' => 'зерно',
            ],
        ];

        $imagesByCategory = [
            'smartphones' => 'https://images.unsplash.com/photo-1512499617640-c2f999087b5b?auto=format&fit=crop&w=900&q=80',
            'laptops' => 'https://images.unsplash.com/photo-1517430816045-df4b7de11d1d?auto=format&fit=crop&w=900&q=80',
            'gaming-laptops' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80',
            'tvs' => 'https://images.unsplash.com/photo-1508898578281-774ac4893a0c?auto=format&fit=crop&w=1000&q=80',
            'headphones' => 'https://images.unsplash.com/photo-1459257868276-5e65389e2722?auto=format&fit=crop&w=900&q=80',
            'vacuums' => 'https://images.unsplash.com/photo-1582719478260-99b4a171112b?auto=format&fit=crop&w=900&q=80',
            'coffee-machines' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=900&q=80',
        ];

        $productsByCategory = [
            'smartphones' => [
                ['name' => 'Apple iPhone 15 Pro', 'slug' => 'apple-iphone-15-pro', 'brand' => 'Apple', 'model' => 'A2890', 'price' => 57999, 'discount' => 4000, 'stock' => 20, 'is_new' => true, 'is_hit' => true, 'popularity' => 98, 'description' => 'Титановий корпус, ProMotion 120 Гц і чип A17 Pro.'],
                ['name' => 'Apple iPhone 15', 'slug' => 'apple-iphone-15', 'brand' => 'Apple', 'model' => 'A2891', 'price' => 41999, 'discount' => 2500, 'stock' => 34, 'is_new' => true, 'is_hit' => true, 'popularity' => 92, 'description' => 'Алюмінієвий корпус і камера з ProRAW.'],
                ['name' => 'Samsung Galaxy S24 Ultra', 'slug' => 'samsung-galaxy-s24-ultra', 'brand' => 'Samsung', 'model' => 'SM-S918B', 'price' => 48999, 'discount' => 3000, 'stock' => 27, 'is_new' => true, 'is_hit' => true, 'popularity' => 95, 'description' => 'S Pen, 200 Мп і потужна батарея 5000 мАг.'],
                ['name' => 'Samsung Galaxy S24', 'slug' => 'samsung-galaxy-s24', 'brand' => 'Samsung', 'model' => 'SM-S911B', 'price' => 35999, 'discount' => 2000, 'stock' => 40, 'is_new' => true, 'is_hit' => true, 'popularity' => 90, 'description' => 'Стабільний AI-камерний блок і Dynamic AMOLED.'],
                ['name' => 'Google Pixel 8 Pro', 'slug' => 'google-pixel-8-pro', 'brand' => 'Google', 'model' => 'GA16977-US', 'price' => 36999, 'discount' => 1500, 'stock' => 22, 'is_new' => true, 'is_hit' => true, 'popularity' => 87, 'description' => 'Tensor G3 і чистий Android з Pro-камерою.'],
                ['name' => 'Google Pixel 8', 'slug' => 'google-pixel-8', 'brand' => 'Google', 'model' => 'GA16963-US', 'price' => 26999, 'discount' => 1000, 'stock' => 30, 'is_new' => true, 'is_hit' => false, 'popularity' => 82, 'description' => 'Компактний корпус, ночний режим і посилений акумулятор.'],
                ['name' => 'OnePlus 12', 'slug' => 'oneplus-12', 'brand' => 'OnePlus', 'model' => 'CPH2613', 'price' => 30999, 'discount' => 1200, 'stock' => 25, 'is_new' => false, 'is_hit' => true, 'popularity' => 84, 'description' => 'FLOW Display, Snapdragon 8 Gen 3 та Hasselblad.'],
                ['name' => 'Xiaomi 14 Pro', 'slug' => 'xiaomi-14-pro', 'brand' => 'Xiaomi', 'model' => '2203133G', 'price' => 32999, 'discount' => 1700, 'stock' => 28, 'is_new' => true, 'is_hit' => false, 'popularity' => 80, 'description' => 'Leica-камера, 120 Вт зарядка і Snapdragon 8 Gen 3.'],
                ['name' => 'Sony Xperia 1 V', 'slug' => 'sony-xperia-1-v', 'brand' => 'Sony', 'model' => 'XQ-CT72', 'price' => 39999, 'discount' => 2200, 'stock' => 15, 'is_new' => false, 'is_hit' => false, 'popularity' => 74, 'description' => '4K OLED, 21:9 і фірмовий камерний режим.'],
                ['name' => 'Vivo X100 Pro', 'slug' => 'vivo-x100-pro', 'brand' => 'Vivo', 'model' => 'V2245', 'price' => 27999, 'discount' => 900, 'stock' => 18, 'is_new' => true, 'is_hit' => false, 'popularity' => 71, 'description' => 'X-Image Engine, 1" сенсор і 120 Гц AMOLED.' ],
            ],
            'laptops' => [
                ['name' => 'MacBook Air 15', 'slug' => 'macbook-air-15', 'brand' => 'Apple', 'model' => 'M2 15"', 'price' => 45999, 'discount' => 3500, 'stock' => 12, 'is_new' => true, 'is_hit' => true, 'popularity' => 93, 'description' => 'Легкий корпус, M2 та Liquid Retina.'],
                ['name' => 'MacBook Pro 14', 'slug' => 'macbook-pro-14', 'brand' => 'Apple', 'model' => 'M3 Pro', 'price' => 62999, 'discount' => 4500, 'stock' => 8, 'is_new' => true, 'is_hit' => true, 'popularity' => 96, 'description' => 'XDR, ProMotion 120 Гц та порт Thunderbolt 4.'],
                ['name' => 'Dell XPS 13 Plus', 'slug' => 'dell-xps-13-plus', 'brand' => 'Dell', 'model' => 'XPS13-9330', 'price' => 55999, 'discount' => 2500, 'stock' => 14, 'is_new' => false, 'is_hit' => true, 'popularity' => 88, 'description' => 'OLED-дисплей та інтелектуальна клавіатура.'],
                ['name' => 'HP Spectre x360 14', 'slug' => 'hp-spectre-x360-14', 'brand' => 'HP', 'model' => 'OLED', 'price' => 40999, 'discount' => 2200, 'stock' => 19, 'is_new' => false, 'is_hit' => false, 'popularity' => 76, 'description' => 'Конвертований корпус, стилус та OLED.'],
                ['name' => 'Lenovo ThinkPad X1 Carbon Gen 12', 'slug' => 'lenovo-thinkpad-x1-carbon-gen-12', 'brand' => 'Lenovo', 'model' => 'X1C Gen 12', 'price' => 48999, 'discount' => 1800, 'stock' => 16, 'is_new' => true, 'is_hit' => false, 'popularity' => 81, 'description' => 'Business-клас, TrackPoint і тонкий корпус.'],
                ['name' => 'Asus ZenBook Duo 14', 'slug' => 'asus-zenbook-duo-14', 'brand' => 'Asus', 'model' => 'UX8406', 'price' => 39999, 'discount' => 1500, 'stock' => 13, 'is_new' => false, 'is_hit' => false, 'popularity' => 69, 'description' => 'Додатковий сенсорний екран з топовою графікою.'],
                ['name' => 'Microsoft Surface Laptop 5', 'slug' => 'microsoft-surface-laptop-5', 'brand' => 'Microsoft', 'model' => 'Surface Laptop 5', 'price' => 36999, 'discount' => 1800, 'stock' => 14, 'is_new' => true, 'is_hit' => false, 'popularity' => 74, 'description' => 'PixelSense дисплей, гладкий корпус та Windows 11.'],
                ['name' => 'Acer Swift 5', 'slug' => 'acer-swift-5', 'brand' => 'Acer', 'model' => 'SF514-59', 'price' => 28999, 'discount' => 900, 'stock' => 21, 'is_new' => false, 'is_hit' => false, 'popularity' => 66, 'description' => 'Легка конструкція, магнієвий сплав і 14" IPS.'],
                ['name' => 'MSI Prestige 14', 'slug' => 'msi-prestige-14', 'brand' => 'MSI', 'model' => 'Prestige 14 Evo', 'price' => 31999, 'discount' => 2000, 'stock' => 11, 'is_new' => false, 'is_hit' => false, 'popularity' => 70, 'description' => 'Для креативу, із RTX 4050 і 1 ТБ SSD.'],
                ['name' => 'Huawei MateBook 16', 'slug' => 'huawei-matebook-16', 'brand' => 'Huawei', 'model' => 'MateBook 16', 'price' => 30999, 'discount' => 1300, 'stock' => 17, 'is_new' => false, 'is_hit' => false, 'popularity' => 68, 'description' => '16-дюймовий екран та батарея на весь день.'],
            ],
            // Additional categories go here...
        ];

        // Build catalog from definitions
        $catalog = [];

        foreach ($productsByCategory as $categorySlug => $items) {
            foreach ($items as $item) {
                $catalog[] = array_merge($item, [
                    'category' => $categorySlug,
                    'specifications' => $categorySpecs[$categorySlug] ?? [],
                    'images' => [$imagesByCategory[$categorySlug] ?? 'https://images.unsplash.com/photo-1525182008055-f88b95ff7980?auto=format&fit=crop&w=900&q=80'],
                ]);
            }
        }

        foreach ($catalog as $item) {
            $category = Category::where('slug', $item['category'])->first();

            if (! $category) {
                continue;
            }

            $product = Product::updateOrCreate([
                'slug' => $item['slug'],
            ], [
                'category_id' => $category->id,
                'name' => $item['name'],
                'brand' => $item['brand'],
                'model' => $item['model'],
                'price' => $item['price'],
                'discount' => $item['discount'] ?? 0,
                'stock' => $item['stock'],
                'description' => $item['description'],
                'specifications' => $item['specifications'],
                'popularity' => $item['popularity'] ?? 0,
                'is_new' => $item['is_new'] ?? false,
                'is_hit' => $item['is_hit'] ?? false,
                'is_active' => $item['is_active'] ?? true,
            ]);

            ProductImage::where('product_id', $product->id)->delete();

            foreach ($item['images'] as $index => $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imagePath,
                    'is_main' => $index === 0,
                    'sort' => $index,
                ]);
            }
        }
    }
}
