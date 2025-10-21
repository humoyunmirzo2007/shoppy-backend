<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Erkaklar futbolkasi',
                'description' => '100% paxta, yozgi futbolka',
                'category_id' => 11, // Erkaklar kiyimi
                'brand_id' => 1, // Nike
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'iPhone 15 Pro',
                'description' => 'Apple iPhone 15 Pro smartfon',
                'category_id' => 17, // iPhone
                'brand_id' => 3, // Apple
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Samsung Galaxy S24',
                'description' => 'Samsung Galaxy S24 smartfon',
                'category_id' => 18, // Samsung
                'brand_id' => 4, // Samsung
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Ayollar ko\'ylagi',
                'description' => 'Zamonaviy ayollar ko\'ylagi',
                'category_id' => 12, // Ayollar kiyimi
                'brand_id' => 2, // Adidas
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'LG Smart TV',
                'description' => '55 dyuymli LG Smart televizor',
                'category_id' => 7, // Televizorlar
                'brand_id' => 6, // LG
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \DB::table('products')->insert($products);
    }
}
