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

        DB::table('categories')->insert([
            ['name' => 'Televizorlar'],
            ['name' => 'Muzlatkichlar'],
            ['name' => 'Kir yuvish mashinalari'],
            ['name' => 'Changyutgichlar'],
            ['name' => 'Konditsionerlar'],
            ['name' => 'Gaz plitalari'],
            ['name' => 'Mikroto‘lqinli pechlar'],
            ['name' => 'Suv isitgichlar'],
            ['name' => 'Dazmollar'],
            ['name' => 'Blender va mikserlar'],
        ]);

        DB::table('suppliers')->insert([
            [
                'name' => 'Texno World',
                'phone_number' => '998909001122',
                'address' => 'Toshkent shahar, Chilonzor tumani, Bunyodkor ko‘chasi 15',
                'debt' => 0,

            ],
            [
                'name' => 'Smart Electronics',
                'phone_number' => '998935554433',
                'address' => 'Samarqand shahar, Registon ko‘chasi 21',
                'debt' => 0,

            ],
            [
                'name' => 'Mega Technik',
                'phone_number' => '998977788990',
                'address' => 'Namangan shahar, Bobur shoh ko‘chasi 45',
                'debt' => 0,

            ],
            [
                'name' => 'Electro Service',
                'phone_number' => '998998887766',
                'address' => 'Andijon shahar, Navro‘z ko‘chasi 10',
                'debt' => 0,

            ],
            [
                'name' => 'Home Comfort',
                'phone_number' => '998903212121',
                'address' => 'Buxoro shahar, G‘ijduvon yo‘li 5',
                'debt' => 0,

            ],
        ]);

        DB::table('cost_types')->insert([
            ['name' => 'Kommunal to\'lovlar uchun'],
            ['name' => 'Do\'kon ijarasi uchun'],
            ['name' => 'Tozalik uchun'],
        ]);

        DB::table('products')->insert([
            ['name' => 'Samsung 55" Smart TV',     'category_id' => 1, 'unit' => 'dona'],
            ['name' => 'LG 43" LED TV',            'category_id' => 1, 'unit' => 'dona'],
            ['name' => 'Artel 300L Muzlatkich',    'category_id' => 2, 'unit' => 'dona'],
            ['name' => 'Samsung NoFrost Muzlatkich', 'category_id' => 2, 'unit' => 'dona'],
            ['name' => 'LG Avtomat Kir Yuvish',    'category_id' => 3, 'unit' => 'dona'],
            ['name' => 'Artel Changyutgich 1600W', 'category_id' => 4, 'unit' => 'dona'],
            ['name' => 'Hisense Konditsioner 24',  'category_id' => 5, 'unit' => 'dona'],
            ['name' => 'Gefest Gaz Plitasi 4x',    'category_id' => 6, 'unit' => 'dona'],
            ['name' => 'Samsung Mikroto‘lqinli Pech', 'category_id' => 7, 'unit' => 'dona'],
            ['name' => 'Ariston Suv Isitgich 50L', 'category_id' => 8, 'unit' => 'dona'],
            ['name' => 'Philips Bug‘li Dazmol',    'category_id' => 9, 'unit' => 'dona'],
            ['name' => 'Bosch Blender 600W',       'category_id' => 10, 'unit' => 'dona'],
            ['name' => 'Kenwood Mikser',           'category_id' => 10, 'unit' => 'dona'],
        ]);

        DB::table('other_sources')->insert([
            ['name' => 'Faktura to\'lovlari', 'type' => 'PRODUCT', 'is_active' => true],
            ['name' => 'Naqd pul to\'lovlari', 'type' => 'PAYMENT', 'is_active' => true],
            ['name' => 'Bank orqali to\'lovlar', 'type' => 'PAYMENT', 'is_active' => true],
            ['name' => 'Kredit to\'lovlari', 'type' => 'PAYMENT', 'is_active' => true],
            ['name' => 'Chegirmalar', 'type' => 'PRODUCT', 'is_active' => true],
            ['name' => 'Qaytarilgan tovarlar', 'type' => 'PRODUCT', 'is_active' => false],
            ['name' => 'Komissiya to\'lovlari', 'type' => 'PAYMENT', 'is_active' => true],
            ['name' => 'Yetkazib berish xizmati', 'type' => 'PAYMENT', 'is_active' => true],
            ['name' => 'Kafolat to\'lovlari', 'type' => 'PAYMENT', 'is_active' => false],
            ['name' => 'Boshqa daromadlar', 'type' => 'PRODUCT', 'is_active' => true],
        ]);

        DB::table('clients')->insert([
            [
                'first_name' => 'Aziz',
                'last_name' => 'Karimov',
                'username' => 'aziz_karimov',
                'phone_number' => '998901234567',
                'debt' => 0,
                'chat_id' => '123456789',
                'is_active' => true,
                'avatar' => null,
            ],
            [
                'first_name' => 'Malika',
                'last_name' => 'Yusupova',
                'username' => 'malika_yusupova',
                'phone_number' => '998902345678',
                'debt' => 0,
                'chat_id' => '234567890',
                'is_active' => true,
                'avatar' => null,
            ],
            [
                'first_name' => 'Jamshid',
                'last_name' => 'Toshmatov',
                'username' => 'jamshid_toshmatov',
                'phone_number' => '998903456789',
                'debt' => 0,
                'chat_id' => '345678901',
                'is_active' => true,
                'avatar' => null,
            ],
            [
                'first_name' => 'Dilfuza',
                'last_name' => 'Rahimova',
                'username' => 'dilfuza_rahimova',
                'phone_number' => '998904567890',
                'debt' => 0,
                'chat_id' => '456789012',
                'is_active' => true,
                'avatar' => null,
            ],
            [
                'first_name' => 'Bekzod',
                'last_name' => 'Mirzayev',
                'username' => 'bekzod_mirzayev',
                'phone_number' => '998905678901',
                'debt' => 0,
                'chat_id' => '567890123',
                'is_active' => false,
                'avatar' => null,
            ],
            [
                'first_name' => 'Gulnora',
                'last_name' => 'Karimova',
                'username' => 'gulnora_karimova',
                'phone_number' => '998906789012',
                'debt' => 0,
                'chat_id' => '678901234',
                'is_active' => true,
                'avatar' => null,
            ],
            [
                'first_name' => 'Rustam',
                'last_name' => 'Sobirov',
                'username' => 'rustam_sobirov',
                'phone_number' => '998907890123',
                'debt' => 0,
                'chat_id' => '789012345',
                'is_active' => true,
                'avatar' => null,
            ],
            [
                'first_name' => 'Zarina',
                'last_name' => 'Abdurahimova',
                'username' => 'zarina_abdurahimova',
                'phone_number' => '998908901234',
                'debt' => 0,
                'chat_id' => '890123456',
                'is_active' => true,
                'avatar' => null,
            ],
            [
                'first_name' => 'Shahzod',
                'last_name' => 'Umarov',
                'username' => 'shahzod_umarov',
                'phone_number' => '998909012345',
                'debt' => 0,
                'chat_id' => '901234567',
                'is_active' => true,
                'avatar' => null,
            ],
            [
                'first_name' => 'Maftuna',
                'last_name' => 'Jalilova',
                'username' => 'maftuna_jalilova',
                'phone_number' => '998900123456',
                'debt' => 0,
                'chat_id' => '012345678',
                'is_active' => true,
                'avatar' => null,
            ],
        ]);

        DB::table('payment_types')->insert([
            [
                'name' => 'Naqd pul',
                'residue' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Plastik karta',
                'residue' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Bank o\'tkazmasi',
                'residue' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Click',
                'residue' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Payme',
                'residue' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Uzcard',
                'residue' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Humo',
                'residue' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'USD naqd pul',
                'residue' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'USD bank o\'tkazmasi',
                'residue' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Chegirma',
                'residue' => 0,
                'is_active' => false,
            ],
        ]);
    }
}
