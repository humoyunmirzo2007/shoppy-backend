<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use App\Models\Product;
use App\Modules\Information\Interfaces\ProductInterface;

class ProductService
{
    public function __construct(protected ProductInterface $productRepository) {}

    /**
     * Barcha mahsulotlarni olish
     */
    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $products = $this->productRepository->getAll($data, $fields);

            return [
                'success' => true,
                'data' => $products,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotlarni olishda xatolik yuz berdi',
                'data' => [],
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

            return [
                'success' => true,
                'data' => $product,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotni olishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Yangi mahsulot yaratish
     */
    public function store(array $data): array
    {
        try {
            $product = $this->productRepository->store($data);

            return [
                'success' => true,
                'data' => $product,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulot yaratishda xatolik yuz berdi',
                'data' => null,
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
                'data' => $updatedProduct,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotni yangilashda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Mahsulotni o'chirish
     */
    public function delete(Product $product): array
    {
        try {
            $this->productRepository->delete($product);

            return [
                'success' => true,
                'data' => null,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulotni o\'chirishda xatolik yuz berdi',
                'data' => null,
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
                    'data' => null,
                ];
            }

            $product->is_active = ! $product->is_active;
            $product->save();

            return [
                'success' => true,
                'data' => $product,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulot holatini o\'zgartirishda xatolik yuz berdi',
                'data' => null,
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
                'data' => null,
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
                'data' => null,
            ];
        }
    }

    /**
     * Narx yangilash shablonini yuklab olish
     */
    public function downloadUpdatePriceTemplate(): array
    {
        try {
            // Bu metodni keyinroq to'liq implement qilamiz
            return [
                'success' => true,
                'data' => 'Price update template download functionality',
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Narx yangilash shablonini yuklab olishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Shablon orqali narxlarni yangilash
     */
    public function updatePricesFromTemplate(): array
    {
        try {
            // Bu metodni keyinroq to'liq implement qilamiz
            return [
                'success' => true,
                'data' => 'Price update from template functionality',
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Narxlarni yangilashda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }
}
