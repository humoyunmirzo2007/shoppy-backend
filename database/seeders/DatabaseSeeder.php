<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // ==================== USERS ====================
        DB::table('users')->insert([
            [
                'full_name' => 'Developer',
                'position' => 'Developer',
                'username' => 'developer',
                'phone_number' => '0123456789',
                'password' => Hash::make('password'),
                'is_dev' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'User',
                'position' => 'User',
                'username' => 'user',
                'phone_number' => '9876543210',
                'password' => Hash::make('password'),
                'is_dev' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==================== CATEGORIES ====================
        $categoriesData = [
            ['name' => 'Elektronika', 'description' => 'Elektronika mahsulotlari'],
            ['name' => 'Maishiy texnika', 'description' => 'Maishiy texnika mahsulotlari'],
            ['name' => 'Kiyim-kechak', 'description' => 'Kiyim-kechak va aksessuarlar'],
            ['name' => 'Oziq-ovqat', 'description' => 'Oziq-ovqat mahsulotlari'],
        ];

        foreach ($categoriesData as &$cat) {
            $cat = array_merge($cat, [
                'parent_id' => null,
                'first_parent_id' => null,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('categories')->insert($categoriesData);

        // ==================== BRANDS ====================
        $brands = ['Nike', 'Adidas', 'Apple', 'Samsung', 'Xiaomi', 'LG', 'Sony', 'Bosch', 'HP', 'Lenovo', 'Dell', 'Asus'];
        $brandInserts = [];
        foreach ($brands as $brand) {
            $brandInserts[] = [
                'name' => $brand,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('brands')->insert($brandInserts);

        // ==================== PRODUCT GROUPS ====================
        $brandsMap = DB::table('brands')->pluck('id', 'name');

        $productGroups = [
            ['Nike Sport', 'Nike'],
            ['Adidas Classic', 'Adidas'],
            ['iPhone Seriyasi', 'Apple'],
            ['Galaxy Seriyasi', 'Samsung'],
            ['Xiaomi Flagship', 'Xiaomi'],
            ['LG Smart TV Seriyasi', 'LG'],
            ['PlayStation Seriyasi', 'Sony'],
            ['Bosch Premium', 'Bosch'],
            ['HP Pavilion Seriyasi', 'HP'],
            ['Lenovo ThinkPad Seriyasi', 'Lenovo'],
            ['Dell Professional', 'Dell'],
            ['Asus Gaming', 'Asus'],
        ];

        $productGroupsInsert = [];
        foreach ($productGroups as $group) {
            [$name, $brandName] = $group;
            $productGroupsInsert[] = [
                'name' => $name,
                'brand_id' => $brandsMap[$brandName],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('product_groups')->insert($productGroupsInsert);

        // ==================== ATTRIBUTES ====================
        $attributes = [
            ['Rang', 'select'], ['O\'lcham', 'select'], ['Material', 'select'], ['Jinsi', 'select'],
            ['Xotira', 'select'], ['Ekran o\'lchami', 'select'], ['Protsessor', 'select'],
            ['Kamera', 'select'], ['Batareya', 'select'], ['Operatsion sistema', 'select'],
            ['Og\'irlik', 'number'], ['Narx', 'number'],
        ];

        $attributeInserts = [];
        foreach ($attributes as $attr) {
            $attributeInserts[] = [
                'name' => $attr[0],
                'type' => $attr[1],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('attributes')->insert($attributeInserts);

        // ==================== ATTRIBUTE VALUES ====================
        $attributesMap = DB::table('attributes')->pluck('id', 'name');

        $attributeValues = [
            'Rang' => ['Qizil', 'Ko\'k', 'Yashil', 'Qora', 'Oq', 'Sariq', 'Pushti', 'Binafsha', 'Jigarrang', 'Kulrang'],
            'O\'lcham' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '28', '30', '32'],
            'Material' => ['Paxta', 'Poliester', 'Ipak', 'Yun', 'Teridan', 'Plastik', 'Metall', 'Shisha', 'Karton', 'Qog\'oz'],
            'Jinsi' => ['Erkak', 'Ayol', 'Uniseks', 'Bola'],
            'Xotira' => ['64 GB', '128 GB', '256 GB', '512 GB', '1 TB', '2 TB'],
            'Ekran o\'lchami' => ['5.5"', '6.1"', '6.7"', '13"', '15"', '17"', '24"', '32"'],
            'Protsessor' => ['Apple A17 Pro', 'Snapdragon 8 Gen 3', 'Intel Core i7'],
            'Kamera' => ['48 MP', '200 MP'],
            'Batareya' => ['3000 mAh', '4000 mAh', '5000 mAh', '6000 mAh'],
            'Operatsion sistema' => ['iOS', 'Android', 'Windows', 'macOS', 'Linux'],
        ];

        $attributeValuesInsert = [];
        foreach ($attributeValues as $attrName => $values) {
            $attrId = $attributesMap[$attrName];
            foreach ($values as $val) {
                $attributeValuesInsert[] = [
                    'attribute_id' => $attrId,
                    'value' => $val,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('attribute_values')->insert($attributeValuesInsert);

        // ==================== PRODUCTS ====================
        $categoriesMap = DB::table('categories')->pluck('id', 'name');
        $brandsMap = DB::table('brands')->pluck('id', 'name');
        $productGroupsMap = DB::table('product_groups')->pluck('id', 'name');

        $products = [
            ['Erkaklar futbolkasi', '100% paxta, yozgi futbolka', 'Kiyim-kechak', 'Nike', 'Nike Sport', 'dona', 50000, 10, 55000, 52000],
            ['iPhone 15 Pro', 'Apple iPhone 15 Pro smartfon', 'Elektronika', 'Apple', 'iPhone Seriyasi', 'dona', 15000000, 5, 16000000, 15500000],
            ['Samsung Galaxy S24', 'Samsung Galaxy S24 smartfon', 'Elektronika', 'Samsung', 'Galaxy Seriyasi', 'dona', 12000000, 8, 13000000, 12500000],
            ['Ayollar ko\'ylagi', 'Zamonaviy ayollar ko\'ylagi', 'Kiyim-kechak', 'Adidas', 'Adidas Classic', 'dona', 45000, 15, 50000, 47000],
            ['LG Smart TV', '55 dyuymli LG Smart televizor', 'Maishiy texnika', 'LG', 'LG Smart TV Seriyasi', 'dona', 8000000, 3, 8500000, 8200000],
            ['Xiaomi 14 Pro', 'Xiaomi 14 Pro smartfon', 'Elektronika', 'Xiaomi', 'Xiaomi Flagship', 'dona', 10000000, 7, 11000000, 10500000],
            ['HP Laptop', 'HP Pavilion noutbuk', 'Elektronika', 'HP', 'HP Pavilion Seriyasi', 'dona', 12000000, 4, 13000000, 12500000],
            ['Lenovo ThinkPad', 'Lenovo ThinkPad noutbuk', 'Elektronika', 'Lenovo', 'Lenovo ThinkPad Seriyasi', 'dona', 11000000, 6, 12000000, 11500000],
            ['Dell Monitor', 'Dell 24 dyuymli monitor', 'Elektronika', 'Dell', 'Dell Professional', 'dona', 3000000, 10, 3200000, 3100000],
            ['Asus ROG', 'Asus ROG gaming noutbuk', 'Elektronika', 'Asus', 'Asus Gaming', 'dona', 15000000, 3, 16000000, 15500000],
            ['Sony PlayStation 5', 'Sony PlayStation 5 o\'yin konsoli', 'Elektronika', 'Sony', 'PlayStation Seriyasi', 'dona', 7000000, 5, 7500000, 7200000],
            ['Bosch Muzlatkich', 'Bosch ikki kamerali muzlatkich', 'Maishiy texnika', 'Bosch', 'Bosch Premium', 'dona', 9000000, 2, 9500000, 9200000],
        ];

        $productsInsert = [];
        foreach ($products as $index => $prod) {
            [$name, $desc, $catName, $brandName, $groupName, $unit, $price, $residue, $wholesalePrice, $markupPrice] = $prod;

            $productsInsert[] = [
                'name' => $name,
                'description' => $desc,
                'category_id' => $categoriesMap[$catName],
                'product_group_id' => $productGroupsMap[$groupName],
                'brand_id' => $brandsMap[$brandName],
                'sku' => 'SKU-'.str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'unit' => $unit,
                'is_active' => true,
                'residue' => $residue,
                'price' => $price,
                'markup' => $markupPrice - $price,
                'wholesale_price' => $wholesalePrice,
                'images' => null,
                'main_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('products')->insert($productsInsert);

    }
}
