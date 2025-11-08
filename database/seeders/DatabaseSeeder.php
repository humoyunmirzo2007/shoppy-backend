<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jadvalarni tozalash (agar kerak bo'lsa)
        // DB::table('product_groups')->truncate();
        // DB::table('products')->truncate();
        // DB::table('attribute_values')->truncate();
        // DB::table('attributes')->truncate();
        // DB::table('brands')->truncate();
        // DB::table('categories')->truncate();

        DB::table('users')->insert([
            [
                'id' => 0,
                'full_name' => 'Developer',
                'position' => 'Developer',
                'username' => 'developer',
                'phone_number' => '0123456789',
                'password' => Hash::make('password'),
                'is_dev' => true,
            ],
            [
                'id' => 1,
                'full_name' => 'User',
                'position' => 'User',
                'username' => 'user',
                'phone_number' => '9876543210',
                'password' => Hash::make('password'),
                'is_dev' => false,
            ],
        ]);

        // Categories seed
        $categories = [
            // Ota kategoriyalar
            [
                'id' => 1,
                'name' => 'Elektronika',
                'description' => 'Elektronika mahsulotlari',
                'parent_id' => null,
                'first_parent_id' => null,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Maishiy texnika',
                'description' => 'Maishiy texnika mahsulotlari',
                'parent_id' => null,
                'first_parent_id' => null,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Kiyim-kechak',
                'description' => 'Kiyim-kechak va aksessuarlar',
                'parent_id' => null,
                'first_parent_id' => null,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Oziq-ovqat',
                'description' => 'Oziq-ovqat mahsulotlari',
                'parent_id' => null,
                'first_parent_id' => null,
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Elektronika quyi kategoriyalari
            [
                'id' => 5,
                'name' => 'Smartfonlar',
                'description' => 'Smartfonlar va aksessuarlar',
                'parent_id' => 1,
                'first_parent_id' => 1,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Noutbuklar',
                'description' => 'Noutbuklar va kompyuterlar',
                'parent_id' => 1,
                'first_parent_id' => 1,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Televizorlar',
                'description' => 'Televizorlar va monitorlar',
                'parent_id' => 1,
                'first_parent_id' => 1,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Maishiy texnika quyi kategoriyalari
            [
                'id' => 8,
                'name' => 'Muzlatkichlar',
                'description' => 'Muzlatkichlar va sovutgichlar',
                'parent_id' => 2,
                'first_parent_id' => 2,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'Kir yuvish mashinalari',
                'description' => 'Kir yuvish va quritish mashinalari',
                'parent_id' => 2,
                'first_parent_id' => 2,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'name' => 'Changyutgichlar',
                'description' => 'Changyutgichlar va tozalash texnikasi',
                'parent_id' => 2,
                'first_parent_id' => 2,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kiyim-kechak quyi kategoriyalari
            [
                'id' => 11,
                'name' => 'Erkaklar kiyimi',
                'description' => 'Erkaklar uchun kiyim-kechak',
                'parent_id' => 3,
                'first_parent_id' => 3,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'name' => 'Ayollar kiyimi',
                'description' => 'Ayollar uchun kiyim-kechak',
                'parent_id' => 3,
                'first_parent_id' => 3,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 13,
                'name' => 'Bolalar kiyimi',
                'description' => 'Bolalar uchun kiyim-kechak',
                'parent_id' => 3,
                'first_parent_id' => 3,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Oziq-ovqat quyi kategoriyalari
            [
                'id' => 14,
                'name' => 'Gosht mahsulotlari',
                'description' => 'Gosht va gosht mahsulotlari',
                'parent_id' => 4,
                'first_parent_id' => 4,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 15,
                'name' => 'Sut mahsulotlari',
                'description' => 'Sut va sut mahsulotlari',
                'parent_id' => 4,
                'first_parent_id' => 4,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 16,
                'name' => 'Meva-sabzavot',
                'description' => 'Meva va sabzavotlar',
                'parent_id' => 4,
                'first_parent_id' => 4,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Uchinchi darajali kategoriyalar (Smartfonlar quyi kategoriyalari)
            [
                'id' => 17,
                'name' => 'iPhone',
                'description' => 'Apple iPhone smartfonlari',
                'parent_id' => 5,
                'first_parent_id' => 1,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 18,
                'name' => 'Samsung',
                'description' => 'Samsung smartfonlari',
                'parent_id' => 5,
                'first_parent_id' => 1,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 19,
                'name' => 'Xiaomi',
                'description' => 'Xiaomi smartfonlari',
                'parent_id' => 5,
                'first_parent_id' => 1,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Nofaol kategoriya (test uchun)
            [
                'id' => 20,
                'name' => 'Nofaol kategoriya',
                'description' => 'Test uchun nofaol kategoriya',
                'parent_id' => null,
                'first_parent_id' => null,
                'is_active' => false,
                'sort_order' => 99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);

        // Brands seed (kamida 10 ta) - avval brands, keyin product_groups
        $brands = [
            ['id' => 1, 'name' => 'Nike', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Adidas', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Apple', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Samsung', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Xiaomi', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'LG', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Sony', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Bosch', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'HP', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Lenovo', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'Dell', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Asus', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        // Brands jadvalini tozalash va yangi ma'lumotlarni qo'shish
        // Foreign key constraint tufayli avval bog'liq jadvallarni tozalash kerak
        DB::table('product_groups')->delete();
        DB::table('products')->whereNotNull('brand_id')->update(['brand_id' => null]);
        DB::table('brands')->delete();
        DB::table('brands')->insert($brands);

        // Product groups seed (kamida 10 ta)
        $productGroups = [
            ['id' => 1, 'name' => 'iPhone 17', 'brand_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'iPhone 17 Pro', 'brand_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'iPhone 17 Pro Max', 'brand_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Samsung Galaxy S24', 'brand_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Samsung Galaxy S24 Ultra', 'brand_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Xiaomi 14', 'brand_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Xiaomi 14 Pro', 'brand_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'HP Pavilion', 'brand_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Lenovo ThinkPad', 'brand_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Asus ROG', 'brand_id' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'Dell XPS', 'brand_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Sony Bravia', 'brand_id' => 7, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('product_groups')->insert($productGroups);

        // Attributes seed (kamida 10 ta)
        $attributes = [
            ['id' => 1, 'name' => 'Rang', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'O\'lcham', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Material', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Jinsi', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Xotira', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Ekran o\'lchami', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Protsessor', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Kamera', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Batareya', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Operatsion sistema', 'type' => 'select', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'Og\'irlik', 'type' => 'number', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Narx', 'type' => 'number', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('attributes')->insert($attributes);

        // Attribute Values seed (kamida 10 ta har bir atribut uchun)
        $attributeValues = [
            // Rang atribut qiymatlari (attribute_id = 1)
            ['id' => 1, 'attribute_id' => 1, 'value' => 'Qizil', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'attribute_id' => 1, 'value' => 'Ko\'k', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'attribute_id' => 1, 'value' => 'Yashil', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'attribute_id' => 1, 'value' => 'Qora', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'attribute_id' => 1, 'value' => 'Oq', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'attribute_id' => 1, 'value' => 'Sariq', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'attribute_id' => 1, 'value' => 'Pushti', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'attribute_id' => 1, 'value' => 'Binafsha', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'attribute_id' => 1, 'value' => 'Jigarrang', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'attribute_id' => 1, 'value' => 'Kulrang', 'created_at' => now(), 'updated_at' => now()],

            // O'lcham atribut qiymatlari (attribute_id = 2)
            ['id' => 11, 'attribute_id' => 2, 'value' => 'XS', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'attribute_id' => 2, 'value' => 'S', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'attribute_id' => 2, 'value' => 'M', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'attribute_id' => 2, 'value' => 'L', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'attribute_id' => 2, 'value' => 'XL', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'attribute_id' => 2, 'value' => 'XXL', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'attribute_id' => 2, 'value' => 'XXXL', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'attribute_id' => 2, 'value' => '28', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'attribute_id' => 2, 'value' => '30', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'attribute_id' => 2, 'value' => '32', 'created_at' => now(), 'updated_at' => now()],

            // Material atribut qiymatlari (attribute_id = 3)
            ['id' => 21, 'attribute_id' => 3, 'value' => 'Paxta', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'attribute_id' => 3, 'value' => 'Poliester', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'attribute_id' => 3, 'value' => 'Ipak', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'attribute_id' => 3, 'value' => 'Yun', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'attribute_id' => 3, 'value' => 'Teridan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'attribute_id' => 3, 'value' => 'Plastik', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'attribute_id' => 3, 'value' => 'Metall', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'attribute_id' => 3, 'value' => 'Shisha', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'attribute_id' => 3, 'value' => 'Karton', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'attribute_id' => 3, 'value' => 'Qog\'oz', 'created_at' => now(), 'updated_at' => now()],

            // Jinsi atribut qiymatlari (attribute_id = 4)
            ['id' => 31, 'attribute_id' => 4, 'value' => 'Erkak', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'attribute_id' => 4, 'value' => 'Ayol', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'attribute_id' => 4, 'value' => 'Uniseks', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'attribute_id' => 4, 'value' => 'Bola', 'created_at' => now(), 'updated_at' => now()],

            // Xotira atribut qiymatlari (attribute_id = 5)
            ['id' => 35, 'attribute_id' => 5, 'value' => '64 GB', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 36, 'attribute_id' => 5, 'value' => '128 GB', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'attribute_id' => 5, 'value' => '256 GB', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 38, 'attribute_id' => 5, 'value' => '512 GB', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 39, 'attribute_id' => 5, 'value' => '1 TB', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 40, 'attribute_id' => 5, 'value' => '2 TB', 'created_at' => now(), 'updated_at' => now()],

            // Ekran o'lchami atribut qiymatlari (attribute_id = 6)
            ['id' => 41, 'attribute_id' => 6, 'value' => '5.5"', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 42, 'attribute_id' => 6, 'value' => '6.1"', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 43, 'attribute_id' => 6, 'value' => '6.7"', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 44, 'attribute_id' => 6, 'value' => '13"', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 45, 'attribute_id' => 6, 'value' => '15"', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 46, 'attribute_id' => 6, 'value' => '17"', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 47, 'attribute_id' => 6, 'value' => '24"', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 48, 'attribute_id' => 6, 'value' => '32"', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 49, 'attribute_id' => 6, 'value' => '55"', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 50, 'attribute_id' => 6, 'value' => '65"', 'created_at' => now(), 'updated_at' => now()],

            // Protsessor atribut qiymatlari (attribute_id = 7)
            ['id' => 51, 'attribute_id' => 7, 'value' => 'Apple A17 Pro', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 52, 'attribute_id' => 7, 'value' => 'Snapdragon 8 Gen 3', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 53, 'attribute_id' => 7, 'value' => 'Exynos 2400', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 54, 'attribute_id' => 7, 'value' => 'Intel Core i5', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 55, 'attribute_id' => 7, 'value' => 'Intel Core i7', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 56, 'attribute_id' => 7, 'value' => 'AMD Ryzen 5', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 57, 'attribute_id' => 7, 'value' => 'AMD Ryzen 7', 'created_at' => now(), 'updated_at' => now()],

            // Kamera atribut qiymatlari (attribute_id = 8)
            ['id' => 58, 'attribute_id' => 8, 'value' => '12 MP', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 59, 'attribute_id' => 8, 'value' => '48 MP', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 60, 'attribute_id' => 8, 'value' => '50 MP', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 61, 'attribute_id' => 8, 'value' => '108 MP', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 62, 'attribute_id' => 8, 'value' => '200 MP', 'created_at' => now(), 'updated_at' => now()],

            // Batareya atribut qiymatlari (attribute_id = 9)
            ['id' => 63, 'attribute_id' => 9, 'value' => '3000 mAh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 64, 'attribute_id' => 9, 'value' => '4000 mAh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 65, 'attribute_id' => 9, 'value' => '5000 mAh', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 66, 'attribute_id' => 9, 'value' => '6000 mAh', 'created_at' => now(), 'updated_at' => now()],

            // Operatsion sistema atribut qiymatlari (attribute_id = 10)
            ['id' => 67, 'attribute_id' => 10, 'value' => 'iOS', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 68, 'attribute_id' => 10, 'value' => 'Android', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 69, 'attribute_id' => 10, 'value' => 'Windows', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 70, 'attribute_id' => 10, 'value' => 'macOS', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 71, 'attribute_id' => 10, 'value' => 'Linux', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('attribute_values')->insert($attributeValues);

        // Products seed (kamida 10 ta)
        $products = [
            ['id' => 1, 'name' => 'Erkaklar futbolkasi', 'description' => '100% paxta, yozgi futbolka', 'category_id' => 11, 'brand_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'iPhone 15 Pro', 'description' => 'Apple iPhone 15 Pro smartfon', 'category_id' => 17, 'brand_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Samsung Galaxy S24', 'description' => 'Samsung Galaxy S24 smartfon', 'category_id' => 18, 'brand_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Ayollar ko\'ylagi', 'description' => 'Zamonaviy ayollar ko\'ylagi', 'category_id' => 12, 'brand_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'LG Smart TV', 'description' => '55 dyuymli LG Smart televizor', 'category_id' => 7, 'brand_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Xiaomi 14 Pro', 'description' => 'Xiaomi 14 Pro smartfon', 'category_id' => 19, 'brand_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'HP Laptop', 'description' => 'HP Pavilion noutbuk', 'category_id' => 6, 'brand_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Lenovo ThinkPad', 'description' => 'Lenovo ThinkPad noutbuk', 'category_id' => 6, 'brand_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Dell Monitor', 'description' => 'Dell 24 dyuymli monitor', 'category_id' => 7, 'brand_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Asus ROG', 'description' => 'Asus ROG gaming noutbuk', 'category_id' => 6, 'brand_id' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'Sony PlayStation 5', 'description' => 'Sony PlayStation 5 o\'yin konsoli', 'category_id' => 1, 'brand_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Bosch Muzlatkich', 'description' => 'Bosch ikki kamerali muzlatkich', 'category_id' => 8, 'brand_id' => 8, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('products')->insert($products);
    }
}
