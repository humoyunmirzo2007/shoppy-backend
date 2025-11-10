<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use App\Models\Product;
use App\Modules\Information\Interfaces\AttributeValueInterface;
use App\Modules\Information\Interfaces\ProductAttributeInterface;
use App\Modules\Information\Interfaces\ProductGroupInterface;
use App\Modules\Information\Interfaces\ProductInterface;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        protected ProductInterface $productRepository,
        protected ProductGroupInterface $productGroupRepository,
        protected ProductAttributeInterface $productAttributeRepository,
        protected AttributeValueInterface $attributeValueRepository
    ) {}

    /**
     * Barcha mahsulotlarni olish
     */
    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $products = $this->productRepository->getAll($data, $fields);

            return [
                'success' => true,
                'message' => 'Mahsulotlar muvaffaqiyatli olindi',
                'data' => $products,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotlarni olishda xatolik yuz berdi',
            ];
        }
    }

    /**
     * ID bo'yicha mahsulotni olish
     */
    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $product = $this->productRepository->getById($id, $fields);

            if (! $product) {
                return [
                    'success' => false,
                    'message' => 'Mahsulot topilmadi',
                ];
            }

            return [
                'success' => true,
                'message' => 'Mahsulot muvaffaqiyatli olindi',
                'data' => $product,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotni olishda xatolik yuz berdi',
            ];
        }
    }

    /**
     * Product group ID bo'yicha mahsulotlarni olish
     */
    public function getByProductGroupId(int $productGroupId, ?array $fields = ['*']): array
    {
        try {
            $products = $this->productRepository->getByProductGroupId($productGroupId, $fields);

            return [
                'success' => true,
                'message' => 'Mahsulotlar muvaffaqiyatli olindi',
                'data' => $products,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotlarni olishda xatolik yuz berdi',
            ];
        }
    }

    /**
     * Yangi mahsulot yaratish
     */
    public function store(array $data): array
    {
        try {
            DB::beginTransaction();

            $productGroupData = [
                'name' => $data['name'],
                'brand_id' => $data['brand_id'],
            ];
            $productGroupResult = $this->productGroupRepository->store($productGroupData);

            if (! $productGroupResult) {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Mahsulot guruhini yaratishda xatolik yuz berdi',
                ];
            }

            $productGroupId = $productGroupResult->id;
            $products = $data['products'] ?? [];

            $request = request();
            $productIndex = 0;
            $productsToInsert = [];
            $productAttributesToInsert = [];
            $productIndexToAttributesMap = [];

            foreach ($products as $productData) {
                $images = [];
                $mainImage = null;

                $productImages = $request->file("products.{$productIndex}.images");

                if ($productImages && is_array($productImages)) {
                    foreach ($productImages as $image) {
                        if ($image && $image->isValid()) {
                            $imagePath = $image->store('products', 'public');
                            $images[] = $imagePath;
                        }
                    }
                } elseif ($productImages && $productImages->isValid()) {
                    $imagePath = $productImages->store('products', 'public');
                    $images[] = $imagePath;
                }

                if (isset($productData['main_image']) && is_string($productData['main_image']) && ! empty($images)) {
                    if (in_array($productData['main_image'], $images)) {
                        $mainImage = $productData['main_image'];
                    } else {
                        $mainImage = $images[0];
                    }
                } elseif (! empty($images)) {
                    $mainImage = $images[0];
                }

                // SKU yaratish
                $sku = $this->generateSku($productGroupResult->name, $productData['attributes'] ?? []);

                $productsToInsert[] = [
                    'name_uz' => $productData['name_uz'],
                    'name_ru' => $productData['name_ru'],
                    'category_id' => $data['category_id'],
                    'product_group_id' => $productGroupId,
                    'brand_id' => $data['brand_id'],
                    'sku' => $sku,
                    'price' => $productData['price'],
                    'wholesale_price' => $productData['wholesale_price'],
                    'description_uz' => $productData['description_uz'] ?? null,
                    'description_ru' => $productData['description_ru'] ?? null,
                    'images' => ! empty($images) ? json_encode($images) : null,
                    'main_image' => $mainImage ? json_encode($mainImage) : null,
                    'unit' => 'dona',
                    'is_active' => true,
                    'residue' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (isset($productData['attributes']) && is_array($productData['attributes'])) {
                    $productIndexToAttributesMap[$productIndex] = $productData['attributes'];
                }

                $productIndex++;
            }

            $createdProducts = [];
            if (! empty($productsToInsert)) {
                $createdProducts = $this->productRepository->storeBulk($productsToInsert);
            }

            foreach ($createdProducts as $index => $product) {
                if (isset($productIndexToAttributesMap[$index]) && is_array($productIndexToAttributesMap[$index])) {
                    foreach ($productIndexToAttributesMap[$index] as $attribute) {
                        $productAttributesToInsert[] = [
                            'product_id' => $product->id,
                            'attribute_value_id' => $attribute['value_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            if (! empty($productAttributesToInsert)) {
                $this->productAttributeRepository->storeBulk($productAttributesToInsert);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Mahsulotlar muvaffaqiyatli yaratildi',
                'data' => [
                    'product_group' => $productGroupResult,
                    'products' => $createdProducts,
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulot yaratishda xatolik yuz berdi',
            ];
        }
    }

    /**
     * Mahsulotni yangilash
     */
    public function update(Product $product, array $data): array
    {
        try {
            $updatedProduct = $this->productRepository->update($product, $data);

            return [
                'success' => true,
                'message' => 'Mahsulot muvaffaqiyatli yangilandi',
                'data' => $updatedProduct,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotni yangilashda xatolik yuz berdi',
            ];
        }
    }

    /**
     * Mahsulot holatini o'zgartirish
     */
    public function toggleActive(int $id): array
    {
        try {
            $product = $this->productRepository->getById($id);

            if (! $product) {
                return [
                    'success' => false,
                    'message' => 'Mahsulot topilmadi',
                ];
            }

            $updatedProduct = $this->productRepository->toggleActive($product);

            return [
                'success' => true,
                'message' => 'Mahsulot holati muvaffaqiyatli o\'zgartirildi',
                'data' => $updatedProduct,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulot holatini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }

    /**
     * Mahsulotlar shablonini yuklab olish
     */
    public function downloadTemplate(): array
    {
        try {
            // Bu metodni keyinroq to'liq implement qilamiz
            return [
                'success' => true,
                'data' => 'Template download functionality',
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Shablonni yuklab olishda xatolik yuz berdi',
            ];
        }
    }

    /**
     * Mahsulotlarni import qilish
     */
    public function import(): array
    {
        try {
            // Bu metodni keyinroq to'liq implement qilamiz
            return [
                'success' => true,
                'data' => 'Import functionality',
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotlarni import qilishda xatolik yuz berdi',
            ];
        }
    }

    /**
     * SKU kodini yaratish
     * Format: PRODUCTGROUP-ATTRIBUTE1-ATTRIBUTE2
     * Masalan: TSHIRT-RED-M
     */
    private function generateSku(string $productGroupName, array $attributes): string
    {
        // Product group nomidan qisqa kod yaratish
        $groupCode = $this->generateCodeFromName($productGroupName);

        // Attribute valuelardan kodlar yaratish
        $attributeCodes = [];
        if (! empty($attributes)) {
            foreach ($attributes as $attribute) {
                if (isset($attribute['value_id'])) {
                    $attributeValue = $this->attributeValueRepository->getById($attribute['value_id']);
                    if ($attributeValue && $attributeValue->value_uz) {
                        $attributeCodes[] = $this->generateCodeFromName($attributeValue->value_uz);
                    }
                }
            }
        }

        // SKU ni birlashtirish
        $skuParts = array_merge([$groupCode], $attributeCodes);

        return strtoupper(implode('-', $skuParts));
    }

    /**
     * Nomdan qisqa kod yaratish (o'zbek lotin harflarida)
     * Masalan: "Erkaklar futbolkasi" -> "ERKAKLAR-FUTBOLKASI" yoki "ERK-FUT"
     * "Qizil" -> "QIZIL" yoki "QIZ"
     */
    private function generateCodeFromName(string $name): string
    {
        // Kirill harflarini o'zbek lotin harflariga o'girish
        $transliteration = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'j', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'x', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'i', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'J', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'X', 'Ц' => 'TS', 'Ч' => 'CH',
            'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '', 'Ы' => 'I', 'Ь' => '',
            'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',
        ];

        $name = strtr($name, $transliteration);

        // Maxsus so'zlar uchun qisqa kodlar (o'zbek lotin harflarida)
        $shortCodes = [
            'futbolka' => 'FUTBOLKA',
            'futbolki' => 'FUTBOLKA',
            'ko\'ylak' => 'KOYLAK',
            'ko\'ylagi' => 'KOYLAK',
            'qizil' => 'QIZIL',
            'qora' => 'QORA',
            'oq' => 'OQ',
            'yashil' => 'YASHIL',
            'sariq' => 'SARIQ',
            'ko\'k' => 'KOK',
            'm' => 'M',
            'l' => 'L',
            'xl' => 'XL',
            'xxl' => 'XXL',
            's' => 'S',
            'erkaklar' => 'ERKAKLAR',
            'ayollar' => 'AYOLLAR',
        ];

        $nameLower = mb_strtolower($name);
        foreach ($shortCodes as $key => $code) {
            if (str_contains($nameLower, $key)) {
                return $code;
            }
        }

        // Agar maxsus kod topilmasa, nomdan qisqa kod yaratish
        // Faqat o'zbek lotin harflari, raqamlar va bo'shliqlarni qoldirish
        $code = preg_replace('/[^a-zA-Z0-9\s\'ўқғҳЎҚҒҲ]/u', '', $name);

        // Bo'shliqlarni olib tashlash va katta harflarga o'girish
        $code = strtoupper(trim($code));

        // Agar kod bo'sh bo'lsa yoki juda qisqa bo'lsa, boshqa usul
        if (empty($code) || mb_strlen($code) < 2) {
            $words = explode(' ', $name);
            $code = '';
            foreach ($words as $word) {
                $word = trim($word);
                if (mb_strlen($word) > 0) {
                    $code .= mb_strtoupper(mb_substr($word, 0, 1));
                }
            }
            if (empty($code)) {
                $code = 'MAHSULOT';
            }
        }

        // Kodni 15 ta belgidan oshmasligi uchun qisqartirish
        return mb_strtoupper(mb_substr($code, 0, 15));
    }
}
