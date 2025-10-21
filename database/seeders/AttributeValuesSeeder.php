<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AttributeValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributeValues = [
            // Rang atribut qiymatlari (attribute_id = 1)
            [
                'id' => 1,
                'attribute_id' => 1,
                'value' => 'Qizil',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'attribute_id' => 1,
                'value' => 'Ko\'k',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'attribute_id' => 1,
                'value' => 'Yashil',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'attribute_id' => 1,
                'value' => 'Qora',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'attribute_id' => 1,
                'value' => 'Oq',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // O'lcham atribut qiymatlari (attribute_id = 2)
            [
                'id' => 6,
                'attribute_id' => 2,
                'value' => 'XS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'attribute_id' => 2,
                'value' => 'S',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'attribute_id' => 2,
                'value' => 'M',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'attribute_id' => 2,
                'value' => 'L',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'attribute_id' => 2,
                'value' => 'XL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 11,
                'attribute_id' => 2,
                'value' => 'XXL',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Material atribut qiymatlari (attribute_id = 3)
            [
                'id' => 12,
                'attribute_id' => 3,
                'value' => 'Paxta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 13,
                'attribute_id' => 3,
                'value' => 'Poliester',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 14,
                'attribute_id' => 3,
                'value' => 'Ipak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 15,
                'attribute_id' => 3,
                'value' => 'Yun',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Jinsi atribut qiymatlari (attribute_id = 4)
            [
                'id' => 16,
                'attribute_id' => 4,
                'value' => 'Erkak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 17,
                'attribute_id' => 4,
                'value' => 'Ayol',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 18,
                'attribute_id' => 4,
                'value' => 'Uniseks',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Xotira atribut qiymatlari (attribute_id = 5)
            [
                'id' => 19,
                'attribute_id' => 5,
                'value' => '64 GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 20,
                'attribute_id' => 5,
                'value' => '128 GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 21,
                'attribute_id' => 5,
                'value' => '256 GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 22,
                'attribute_id' => 5,
                'value' => '512 GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Ekran o'lchami atribut qiymatlari (attribute_id = 6)
            [
                'id' => 23,
                'attribute_id' => 6,
                'value' => '5.5"',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 24,
                'attribute_id' => 6,
                'value' => '6.1"',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 25,
                'attribute_id' => 6,
                'value' => '6.7"',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \DB::table('attribute_values')->insert($attributeValues);
    }
}
