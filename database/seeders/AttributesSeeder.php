<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'id' => 1,
                'name' => 'Rang',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'O\'lcham',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Material',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Jinsi',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Xotira',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Ekran o\'lchami',
                'type' => 'select',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \DB::table('attributes')->insert($attributes);
    }
}
