<?php

namespace Database\Seeders;

use App\Models\User;
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
          'id' => 0,
          'full_name'=> 'Developer',
          'position' => 'Developer',
          'username'=> 'developer',
          'phone_number'=> '0123456789',
          'password'=> Hash::make('password'),
          'is_dev' => true,
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
                'address' => "Toshkent shahar, Chilonzor tumani, Bunyodkor ko‘chasi 15"
            ],
            [
                'name' => 'Smart Electronics',
                'phone_number' => '998935554433',
                'address' => "Samarqand shahar, Registon ko‘chasi 21"
            ],
            [
                'name' => 'Mega Technik',
                'phone_number' => '998977788990',
                'address' => "Namangan shahar, Bobur shoh ko‘chasi 45"
            ],
            [
                'name' => 'Electro Service',
                'phone_number' => '998998887766',
                'address' => "Andijon shahar, Navro‘z ko‘chasi 10"
            ],
            [
                'name' => 'Home Comfort',
                'phone_number' => '998903212121',
                'address' => "Buxoro shahar, G‘ijduvon yo‘li 5"
            ],
        ]);

        DB::table('cost_types')->insert([
            ['name'=> 'Kommunal to\'lovlar uchun'],
            ['name'=> 'Do\'kon ijarasi uchun'],
            ['name'=> 'Tozalik uchun'],
        ]);

        DB::table('products')->insert([
            ['name' => 'Samsung 55" Smart TV',     'category_id' => 1, 'unit' => 'dona'],
            ['name' => 'LG 43" LED TV',            'category_id' => 1, 'unit' => 'dona'],
            ['name' => 'Artel 300L Muzlatkich',    'category_id' => 2, 'unit' => 'dona'],
            ['name' => 'Samsung NoFrost Muzlatkich','category_id' => 2,'unit' => 'dona'],
            ['name' => 'LG Avtomat Kir Yuvish',    'category_id' => 3, 'unit' => 'dona'],
            ['name' => 'Artel Changyutgich 1600W', 'category_id' => 4, 'unit' => 'dona'],
            ['name' => 'Hisense Konditsioner 24',  'category_id' => 5, 'unit' => 'dona'],
            ['name' => 'Gefest Gaz Plitasi 4x',    'category_id' => 6, 'unit' => 'dona'],
            ['name' => 'Samsung Mikroto‘lqinli Pech','category_id' => 7, 'unit' => 'dona'],
            ['name' => 'Ariston Suv Isitgich 50L', 'category_id' => 8, 'unit' => 'dona'],
            ['name' => 'Philips Bug‘li Dazmol',    'category_id' => 9, 'unit' => 'dona'],
            ['name' => 'Bosch Blender 600W',       'category_id' => 10,'unit' => 'dona'],
            ['name' => 'Kenwood Mikser',           'category_id' => 10,'unit' => 'dona'],
        ]);

    }
}
