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
                'full_name' => 'User !',
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
            ['name_uz' => 'Elektronika', 'name_ru' => 'Электроника', 'description' => 'Elektronika mahsulotlari'],
            ['name_uz' => 'Maishiy texnika', 'name_ru' => 'Бытовая техника', 'description' => 'Maishiy texnika mahsulotlari'],
            ['name_uz' => 'Kiyim-kechak', 'name_ru' => 'Одежда', 'description' => 'Kiyim-kechak va aksessuarlar'],
            ['name_uz' => 'Oziq-ovqat', 'name_ru' => 'Продукты питания', 'description' => 'Oziq-ovqat mahsulotlari'],
            ['name_uz' => 'Mebellar', 'name_ru' => 'Мебель', 'description' => 'Mebellar va interer buyumlari'],
            ['name_uz' => 'Sport va dam olish', 'name_ru' => 'Спорт и отдых', 'description' => 'Sport anjomlari va dam olish mahsulotlari'],
            ['name_uz' => 'Kosmetika va parfyumeriya', 'name_ru' => 'Косметика и парфюмерия', 'description' => 'Kosmetika va parfyumeriya mahsulotlari'],
            ['name_uz' => 'Kitoblar va o\'quv materiallari', 'name_ru' => 'Книги и учебные материалы', 'description' => 'Kitoblar va o\'quv materiallari'],
            ['name_uz' => 'Avtomobillar va ehtiyot qismlar', 'name_ru' => 'Автомобили и запчасти', 'description' => 'Avtomobillar va ehtiyot qismlar'],
            ['name_uz' => 'Uy va bog\' uchun', 'name_ru' => 'Для дома и сада', 'description' => 'Uy va bog\' uchun mahsulotlar'],
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
            ['Rang', 'Цвет'],
            ['O\'lcham', 'Размер'],
            ['Material', 'Материал'],
            ['Jinsi', 'Пол'],
            ['Xotira', 'Память'],
            ['Ekran o\'lchami', 'Размер экрана'],
            ['Protsessor', 'Процессор'],
            ['Kamera', 'Камера'],
            ['Batareya', 'Батарея'],
            ['Operatsion sistema', 'Операционная система'],
        ];

        $attributeInserts = [];
        foreach ($attributes as $attr) {
            $attributeInserts[] = [
                'name_uz' => $attr[0],
                'name_ru' => $attr[1],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('attributes')->insert($attributeInserts);

        // ==================== ATTRIBUTE VALUES ====================
        $attributesMap = DB::table('attributes')->pluck('id', 'name_uz');

        $attributeValues = [
            'Rang' => [
                ['Qizil', 'Красный'], ['Ko\'k', 'Синий'], ['Yashil', 'Зеленый'], ['Qora', 'Черный'],
                ['Oq', 'Белый'], ['Sariq', 'Желтый'], ['Pushti', 'Розовый'], ['Binafsha', 'Фиолетовый'],
                ['Jigarrang', 'Коричневый'], ['Kulrang', 'Серый'],
            ],
            'O\'lcham' => [
                ['XS', 'XS'], ['S', 'S'], ['M', 'M'], ['L', 'L'], ['XL', 'XL'], ['XXL', 'XXL'],
                ['XXXL', 'XXXL'], ['28', '28'], ['30', '30'], ['32', '32'],
            ],
            'Material' => [
                ['Paxta', 'Хлопок'], ['Poliester', 'Полиэстер'], ['Ipak', 'Шелк'], ['Yun', 'Шерсть'],
                ['Teridan', 'Кожа'], ['Plastik', 'Пластик'], ['Metall', 'Металл'], ['Shisha', 'Стекло'],
                ['Karton', 'Картон'], ['Qog\'oz', 'Бумага'],
            ],
            'Jinsi' => [
                ['Erkak', 'Мужской'], ['Ayol', 'Женский'], ['Uniseks', 'Унисекс'], ['Bola', 'Детский'],
            ],
            'Xotira' => [
                ['64 GB', '64 GB'], ['128 GB', '128 GB'], ['256 GB', '256 GB'], ['512 GB', '512 GB'],
                ['1 TB', '1 TB'], ['2 TB', '2 TB'],
            ],
            'Ekran o\'lchami' => [
                ['5.5"', '5.5"'], ['6.1"', '6.1"'], ['6.7"', '6.7"'], ['13"', '13"'],
                ['15"', '15"'], ['17"', '17"'], ['24"', '24"'], ['32"', '32"'],
            ],
            'Protsessor' => [
                ['Apple A17 Pro', 'Apple A17 Pro'], ['Snapdragon 8 Gen 3', 'Snapdragon 8 Gen 3'],
                ['Intel Core i7', 'Intel Core i7'],
            ],
            'Kamera' => [
                ['48 MP', '48 MP'], ['200 MP', '200 MP'],
            ],
            'Batareya' => [
                ['3000 mAh', '3000 mAh'], ['4000 mAh', '4000 mAh'], ['5000 mAh', '5000 mAh'],
                ['6000 mAh', '6000 mAh'],
            ],
            'Operatsion sistema' => [
                ['iOS', 'iOS'], ['Android', 'Android'], ['Windows', 'Windows'], ['macOS', 'macOS'],
                ['Linux', 'Linux'],
            ],
        ];

        $attributeValuesInsert = [];
        foreach ($attributeValues as $attrName => $values) {
            $attrId = $attributesMap[$attrName];
            foreach ($values as $val) {
                $attributeValuesInsert[] = [
                    'attribute_id' => $attrId,
                    'value_uz' => $val[0],
                    'value_ru' => $val[1],
                    'code' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('attribute_values')->insert($attributeValuesInsert);

        // ==================== PRODUCTS ====================
        $categoriesMap = DB::table('categories')->pluck('id', 'name_uz');
        $brandsMap = DB::table('brands')->pluck('id', 'name');
        $productGroupsMap = DB::table('product_groups')->pluck('id', 'name');

        $products = [
            ['Erkaklar futbolkasi', 'Мужская футболка', '100% paxta, yozgi futbolka', '100% хлопок, летняя футболка', 'Kiyim-kechak', 'Nike', 'Nike Sport', 'dona', 50000, 10, 55000, 52000],
            ['iPhone 15 Pro', 'iPhone 15 Pro', 'Apple iPhone 15 Pro smartfon', 'Смартфон Apple iPhone 15 Pro', 'Elektronika', 'Apple', 'iPhone Seriyasi', 'dona', 15000000, 5, 16000000, 15500000],
            ['Samsung Galaxy S24', 'Samsung Galaxy S24', 'Samsung Galaxy S24 smartfon', 'Смартфон Samsung Galaxy S24', 'Elektronika', 'Samsung', 'Galaxy Seriyasi', 'dona', 12000000, 8, 13000000, 12500000],
            ['Ayollar ko\'ylagi', 'Женское платье', 'Zamonaviy ayollar ko\'ylagi', 'Современное женское платье', 'Kiyim-kechak', 'Adidas', 'Adidas Classic', 'dona', 45000, 15, 50000, 47000],
            ['LG Smart TV', 'LG Smart TV', '55 dyuymli LG Smart televizor', '55-дюймовый LG Smart телевизор', 'Maishiy texnika', 'LG', 'LG Smart TV Seriyasi', 'dona', 8000000, 3, 8500000, 8200000],
            ['Xiaomi 14 Pro', 'Xiaomi 14 Pro', 'Xiaomi 14 Pro smartfon', 'Смартфон Xiaomi 14 Pro', 'Elektronika', 'Xiaomi', 'Xiaomi Flagship', 'dona', 10000000, 7, 11000000, 10500000],
            ['HP Laptop', 'HP Laptop', 'HP Pavilion noutbuk', 'Ноутбук HP Pavilion', 'Elektronika', 'HP', 'HP Pavilion Seriyasi', 'dona', 12000000, 4, 13000000, 12500000],
            ['Lenovo ThinkPad', 'Lenovo ThinkPad', 'Lenovo ThinkPad noutbuk', 'Ноутбук Lenovo ThinkPad', 'Elektronika', 'Lenovo', 'Lenovo ThinkPad Seriyasi', 'dona', 11000000, 6, 12000000, 11500000],
            ['Dell Monitor', 'Dell Monitor', 'Dell 24 dyuymli monitor', '24-дюймовый монитор Dell', 'Elektronika', 'Dell', 'Dell Professional', 'dona', 3000000, 10, 3200000, 3100000],
            ['Asus ROG', 'Asus ROG', 'Asus ROG gaming noutbuk', 'Игровой ноутбук Asus ROG', 'Elektronika', 'Asus', 'Asus Gaming', 'dona', 15000000, 3, 16000000, 15500000],
            ['Sony PlayStation 5', 'Sony PlayStation 5', 'Sony PlayStation 5 o\'yin konsoli', 'Игровая консоль Sony PlayStation 5', 'Elektronika', 'Sony', 'PlayStation Seriyasi', 'dona', 7000000, 5, 7500000, 7200000],
            ['Bosch Muzlatkich', 'Bosch Холодильник', 'Bosch ikki kamerali muzlatkich', 'Двухкамерный холодильник Bosch', 'Maishiy texnika', 'Bosch', 'Bosch Premium', 'dona', 9000000, 2, 9500000, 9200000],
        ];

        $productsInsert = [];
        foreach ($products as $index => $prod) {
            [$nameUz, $nameRu, $descUz, $descRu, $catName, $brandName, $groupName, $unit, $price, $residue, $wholesalePrice, $markupPrice] = $prod;

            $productsInsert[] = [
                'name_uz' => $nameUz,
                'name_ru' => $nameRu,
                'description_uz' => $descUz,
                'description_ru' => $descRu,
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

        // ==================== CLIENTS ====================
        $clientsData = [
            [
                'first_name' => 'Akmal',
                'middle_name' => 'Karim',
                'last_name' => 'Karimov',
                'username' => 'akmal_karimov',
                'phone_number' => '998901234567',
                'debt' => 0,
                'chat_id' => '123456789',
                'is_active' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Dilshoda',
                'middle_name' => null,
                'last_name' => 'Toshmatova',
                'username' => 'dilshoda_t',
                'phone_number' => '998901234568',
                'debt' => 150000,
                'chat_id' => '123456790',
                'is_active' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Javohir',
                'middle_name' => 'Rustam',
                'last_name' => 'Rahimov',
                'username' => 'javohir_r',
                'phone_number' => '998901234569',
                'debt' => 0,
                'chat_id' => '123456791',
                'is_active' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Madina',
                'middle_name' => 'Yusuf',
                'last_name' => 'Yusupova',
                'username' => 'madina_y',
                'phone_number' => '998901234570',
                'debt' => 250000,
                'chat_id' => '123456792',
                'is_active' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Olimjon',
                'middle_name' => null,
                'last_name' => 'Aliyev',
                'username' => 'olimjon_a',
                'phone_number' => '998901234571',
                'debt' => 0,
                'chat_id' => '123456793',
                'is_active' => false,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Sevara',
                'middle_name' => 'Norbek',
                'last_name' => 'Norboyeva',
                'username' => 'sevara_n',
                'phone_number' => '998901234572',
                'debt' => 75000,
                'chat_id' => '123456794',
                'is_active' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Temur',
                'middle_name' => 'Bekmurod',
                'last_name' => 'Bekmurodov',
                'username' => 'temur_b',
                'phone_number' => '998901234573',
                'debt' => 0,
                'chat_id' => '123456795',
                'is_active' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Zuhra',
                'middle_name' => 'Qodir',
                'last_name' => 'Qodirova',
                'username' => 'zuhra_q',
                'phone_number' => '998901234574',
                'debt' => 320000,
                'chat_id' => '123456796',
                'is_active' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Farhod',
                'middle_name' => 'Ismoil',
                'last_name' => 'Ismoilov',
                'username' => 'farhod_i',
                'phone_number' => '998901234575',
                'debt' => 0,
                'chat_id' => '123456797',
                'is_active' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Gulnora',
                'middle_name' => null,
                'last_name' => 'Xasanova',
                'username' => 'gulnora_x',
                'phone_number' => '998901234576',
                'debt' => 180000,
                'chat_id' => '123456798',
                'is_active' => true,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('clients')->insert($clientsData);

    }
}
