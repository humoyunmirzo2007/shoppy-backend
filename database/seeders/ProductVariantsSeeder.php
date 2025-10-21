<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductVariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productVariants = [
            // Erkaklar futbolkasi variantlari (product_id = 1)
            [
                'id' => 1,
                'product_id' => 1,
                'sku' => 'TSHIRT-RED-M',
                'price' => 120000.00,
                'stock' => 15,
                'image_url' => 'https://cdn.example.com/products/tshirt_red_m.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'product_id' => 1,
                'sku' => 'TSHIRT-RED-L',
                'price' => 120000.00,
                'stock' => 10,
                'image_url' => 'https://cdn.example.com/products/tshirt_red_l.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'product_id' => 1,
                'sku' => 'TSHIRT-BLU-M',
                'price' => 120000.00,
                'stock' => 8,
                'image_url' => 'https://cdn.example.com/products/tshirt_blue_m.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'product_id' => 1,
                'sku' => 'TSHIRT-BLU-L',
                'price' => 120000.00,
                'stock' => 12,
                'image_url' => 'https://cdn.example.com/products/tshirt_blue_l.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // iPhone 15 Pro variantlari (product_id = 2)
            [
                'id' => 5,
                'product_id' => 2,
                'sku' => 'IPHONE15PRO-128GB-NATURAL',
                'price' => 12000000.00,
                'stock' => 5,
                'image_url' => 'https://cdn.example.com/products/iphone15pro_128gb_natural.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'product_id' => 2,
                'sku' => 'IPHONE15PRO-256GB-NATURAL',
                'price' => 13500000.00,
                'stock' => 3,
                'image_url' => 'https://cdn.example.com/products/iphone15pro_256gb_natural.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'product_id' => 2,
                'sku' => 'IPHONE15PRO-128GB-BLUE',
                'price' => 12000000.00,
                'stock' => 7,
                'image_url' => 'https://cdn.example.com/products/iphone15pro_128gb_blue.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Samsung Galaxy S24 variantlari (product_id = 3)
            [
                'id' => 8,
                'product_id' => 3,
                'sku' => 'SAMSUNG-S24-128GB-BLACK',
                'price' => 8000000.00,
                'stock' => 10,
                'image_url' => 'https://cdn.example.com/products/samsung_s24_128gb_black.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'product_id' => 3,
                'sku' => 'SAMSUNG-S24-256GB-BLACK',
                'price' => 9000000.00,
                'stock' => 6,
                'image_url' => 'https://cdn.example.com/products/samsung_s24_256gb_black.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Ayollar ko'ylagi variantlari (product_id = 4)
            [
                'id' => 10,
                'product_id' => 4,
                'sku' => 'DRESS-PINK-S',
                'price' => 250000.00,
                'stock' => 8,
                'image_url' => 'https://cdn.example.com/products/dress_pink_s.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 11,
                'product_id' => 4,
                'sku' => 'DRESS-PINK-M',
                'price' => 250000.00,
                'stock' => 12,
                'image_url' => 'https://cdn.example.com/products/dress_pink_m.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // LG Smart TV variantlari (product_id = 5)
            [
                'id' => 12,
                'product_id' => 5,
                'sku' => 'LG-TV-55-BLACK',
                'price' => 5000000.00,
                'stock' => 4,
                'image_url' => 'https://cdn.example.com/products/lg_tv_55_black.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \DB::table('product_variants')->insert($productVariants);
    }
}
