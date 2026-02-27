<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'customer@technodim.local'],
            [
                'name' => 'Марія Коваль',
                'phone' => '380636543210',
                'password' => Hash::make('Customer!2026'),
            ],
        );

        $product = Product::where('slug', 'apple-iphone-15-pro')->first();

        if ($product) {
            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => 5,
                'comment' => 'Ідеально лежить у руці, прекрасна камера та фішки iOS.',
            ]);

            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => 4,
                'comment' => 'Батарея тримає добре, але хотілося б більше сховища у базовій комплектації.',
            ]);
        }
    }
}
