<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Models\ProductVariant;
use App\Modules\Information\Interfaces\ProductVariantInterface;

class ProductVariantService
{
    public function __construct(protected ProductVariantInterface $productVariantRepository) {}

    /**
     * Barcha mahsulot variantlarini olish
     */
    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $productVariants = $this->productVariantRepository->getAll($data, $fields);

            return [
                'success' => true,
                'data' => $productVariants,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Mahsulot variantlarini olishda xatolik yuz berdi',
                'data' => [],
            ];
        }
    }

    /**
     * ID bo'yicha mahsulot variantini olish
     */
    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $productVariant = $this->productVariantRepository->getById($id, $fields);

            return [
                'success' => true,
                'data' => $productVariant,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Mahsulot variantini olishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Yangi mahsulot variantini yaratish
     */
    public function store(array $data): array
    {
        try {
            $productVariant = $this->productVariantRepository->store($data);

            return [
                'success' => true,
                'data' => $productVariant,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Mahsulot variantini yaratishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Mahsulot variantini yangilash
     */
    public function update(ProductVariant $productVariant, array $data): array
    {
        try {
            $updatedProductVariant = $this->productVariantRepository->update($productVariant, $data);

            return [
                'success' => true,
                'data' => $updatedProductVariant,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Mahsulot variantini yangilashda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Mahsulot variantini o'chirish
     */
    public function delete(ProductVariant $productVariant): array
    {
        try {
            $this->productVariantRepository->delete($productVariant);

            return [
                'success' => true,
                'data' => null,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Mahsulot variantini o\'chirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Mahsulot varianti holatini o'zgartirish
     */
    public function toggleActive(int $id): array
    {
        try {
            $productVariant = $this->productVariantRepository->getById($id);

            if (! $productVariant) {
                return [
                    'success' => false,
                    'message' => 'Mahsulot varianti topilmadi',
                    'data' => null,
                ];
            }

            $productVariant->is_active = ! $productVariant->is_active;
            $productVariant->save();

            return [
                'success' => true,
                'data' => $productVariant,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Mahsulot varianti holatini o\'zgartirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }
}
