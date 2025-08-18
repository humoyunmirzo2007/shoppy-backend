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
    }
}
