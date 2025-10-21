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

        // Boshqa seederlarni chaqirish
        $this->call([
            AttributesSeeder::class,
            BrandsSeeder::class,
            AttributeValuesSeeder::class,
            ProductsSeeder::class,
            ProductVariantsSeeder::class,
            VariantAttributesSeeder::class,
        ]);
    }
}
