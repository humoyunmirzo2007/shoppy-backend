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
     * Mavjud mahsulot guruhiga yangi mahsulotlar qo'shish
     */
    public function storeToExistingProductGroup(array $data): array
    {
        try {
            DB::beginTransaction();

            $productGroupId = $data['product_group_id'];
            $productGroup = $this->productGroupRepository->getById($productGroupId);

            if (! $productGroup) {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Mahsulot guruhi topilmadi',
                ];
            }

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

                $sku = $this->generateSku($productGroup->name, $productData['attributes'] ?? []);

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
                'message' => 'Mahsulotlar muvaffaqiyatli qo\'shildi',
                'data' => [
                    'product_group' => $productGroup,
                    'products' => $createdProducts,
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotlarni qo\'shishda xatolik yuz berdi',
            ];
        }
    }

    public function update(array $data, $id): array
    {
        DB::beginTransaction();

        try {
            $request = request();
            $product = $this->productRepository->getById($id);

            if (! $product) {
                return [
                    'success' => false,
                    'message' => 'Mahsulot topilmadi',
                ];
            }

            $fillableKeys = [
                'name_uz', 'name_ru', 'description_uz', 'description_ru',
                'category_id', 'brand_id', 'price', 'wholesale_price',
            ];

            $updateData = [];
            foreach ($fillableKeys as $key) {
                if (array_key_exists($key, $data)) {
                    $updateData[$key] = $data[$key];
                }
            }

            $newImages = [];
            if ($request->hasFile('images')) {
                foreach ((array) $request->file('images') as $image) {
                    if ($image?->isValid()) {
                        $newImages[] = $image->store('products', 'public');
                    }
                }

                if (! empty($newImages)) {
                    $existing = json_decode($product->images ?? '[]', true) ?? [];
                    $merged = array_slice(array_merge($existing, $newImages), 0, 4);
                    $updateData['images'] = json_encode($merged);
                }
            }

            $allImages = isset($updateData['images'])
                ? json_decode($updateData['images'], true) ?? []
                : (json_decode($product->images ?? '[]', true) ?? []);

            $mainImage = $this->resolveMainImage($data, $allImages, $newImages);
            if ($mainImage !== null) {
                $updateData['main_image'] = json_encode($mainImage);
            } elseif (array_key_exists('main_image', $data) && empty($data['main_image'])) {
                $updateData['main_image'] = null;
            }

            $shouldUpdateSku = false;

            if (isset($data['attributes']) && is_array($data['attributes'])) {
                $this->productAttributeRepository->deleteByProductId($product->id);

                $insertData = collect($data['attributes'])
                    ->whereNotNull('value_id')
                    ->map(fn ($a) => [
                        'product_id' => $product->id,
                        'attribute_value_id' => $a['value_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])->toArray();

                if (! empty($insertData)) {
                    $this->productAttributeRepository->storeBulk($insertData);
                }

                $shouldUpdateSku = true;
            }

            if ($shouldUpdateSku || isset($data['brand_id'])) {
                $productGroup = $this->productGroupRepository->getById($product->product_group_id);

                if ($productGroup) {
                    $attributes = $data['attributes'] ?? null;

                    if ($attributes === null) {
                        $product->load('productAttributes');
                        $attributes = $product->productAttributes->map(fn ($pa) => [
                            'value_id' => $pa->attribute_value_id,
                        ])->toArray();
                    }

                    $updateData['sku'] = $this->generateSku($productGroup->name, $attributes);
                }
            }

            if (! empty($updateData)) {
                $this->productRepository->update($product, $updateData);
            } else {
                $product->touch();
            }

            DB::commit();

            $updatedProduct = $this->productRepository->getById($product->id);

            return [
                'success' => true,
                'message' => 'Mahsulot muvaffaqiyatli yangilandi',
                'data' => $updatedProduct,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotni yangilashda xatolik yuz berdi',
            ];
        }
    }

    private function resolveMainImage(array $data, array $allImages, array $newImages): ?string
    {
        if (array_key_exists('main_image', $data)) {
            if (empty($data['main_image'])) {
                return null;
            }

            $main = $data['main_image'];

            if (str_contains($main, '/storage/')) {
                $main = explode('/storage/', $main)[1] ?? $main;
            }

            if (in_array($main, $allImages)) {
                return $main;
            }

            if (! empty($allImages)) {
                return $allImages[0];
            }

            return null;
        }

        if (! empty($newImages)) {
            return $newImages[0];
        }

        return null;
    }

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

    public function downloadTemplate(): array
    {
        try {
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

    public function import(): array
    {
        try {
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

    private function generateSku(string $productGroupName, array $attributes): string
    {
        $groupCode = $this->generateCodeFromName($productGroupName);

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

        $skuParts = array_merge([$groupCode], $attributeCodes);

        return strtoupper(implode('-', $skuParts));
    }

    private function generateCodeFromName(string $name): string
    {
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

        $code = preg_replace('/[^a-zA-Z0-9\s\'ўқғҳЎҚҒҲ]/u', '', $name);

        $code = strtoupper(trim($code));

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

        return mb_strtoupper(mb_substr($code, 0, 15));
    }
}
