<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VariantAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variantAttributes = [
            // Erkaklar futbolkasi variantlari atributlari
            // TSHIRT-RED-M (variant_id = 1)
            [
                'id' => 1,
                'variant_id' => 1,
                'attribute_value_id' => 1, // Qizil
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'variant_id' => 1,
                'attribute_value_id' => 8, // M
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // TSHIRT-RED-L (variant_id = 2)
            [
                'id' => 3,
                'variant_id' => 2,
                'attribute_value_id' => 1, // Qizil
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'variant_id' => 2,
                'attribute_value_id' => 9, // L
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // TSHIRT-BLU-M (variant_id = 3)
            [
                'id' => 5,
                'variant_id' => 3,
                'attribute_value_id' => 2, // Ko'k
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'variant_id' => 3,
                'attribute_value_id' => 8, // M
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // TSHIRT-BLU-L (variant_id = 4)
            [
                'id' => 7,
                'variant_id' => 4,
                'attribute_value_id' => 2, // Ko'k
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'variant_id' => 4,
                'attribute_value_id' => 9, // L
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // iPhone 15 Pro variantlari atributlari
            // IPHONE15PRO-128GB-NATURAL (variant_id = 5)
            [
                'id' => 9,
                'variant_id' => 5,
                'attribute_value_id' => 20, // 128 GB
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'variant_id' => 5,
                'attribute_value_id' => 5, // Oq (Natural)
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // IPHONE15PRO-256GB-NATURAL (variant_id = 6)
            [
                'id' => 11,
                'variant_id' => 6,
                'attribute_value_id' => 21, // 256 GB
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'variant_id' => 6,
                'attribute_value_id' => 5, // Oq (Natural)
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // IPHONE15PRO-128GB-BLUE (variant_id = 7)
            [
                'id' => 13,
                'variant_id' => 7,
                'attribute_value_id' => 20, // 128 GB
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 14,
                'variant_id' => 7,
                'attribute_value_id' => 2, // Ko'k
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Samsung Galaxy S24 variantlari atributlari
            // SAMSUNG-S24-128GB-BLACK (variant_id = 8)
            [
                'id' => 15,
                'variant_id' => 8,
                'attribute_value_id' => 20, // 128 GB
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 16,
                'variant_id' => 8,
                'attribute_value_id' => 4, // Qora
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // SAMSUNG-S24-256GB-BLACK (variant_id = 9)
            [
                'id' => 17,
                'variant_id' => 9,
                'attribute_value_id' => 21, // 256 GB
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 18,
                'variant_id' => 9,
                'attribute_value_id' => 4, // Qora
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Ayollar ko'ylagi variantlari atributlari
            // DRESS-PINK-S (variant_id = 10)
            [
                'id' => 19,
                'variant_id' => 10,
                'attribute_value_id' => 1, // Qizil (Pink)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 20,
                'variant_id' => 10,
                'attribute_value_id' => 7, // S
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // DRESS-PINK-M (variant_id = 11)
            [
                'id' => 21,
                'variant_id' => 11,
                'attribute_value_id' => 1, // Qizil (Pink)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 22,
                'variant_id' => 11,
                'attribute_value_id' => 8, // M
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // LG Smart TV variantlari atributlari
            // LG-TV-55-BLACK (variant_id = 12)
            [
                'id' => 23,
                'variant_id' => 12,
                'attribute_value_id' => 4, // Qora
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \DB::table('variant_attributes')->insert($variantAttributes);
    }
}
