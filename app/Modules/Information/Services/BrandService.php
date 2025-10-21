<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Models\Brand;
use App\Modules\Information\Interfaces\BrandInterface;

class BrandService
{
    public function __construct(protected BrandInterface $brandRepository) {}

    /**
     * Barcha brendlarni olish
     */
    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $brands = $this->brandRepository->getAll($data, $fields);

            return [
                'success' => true,
                'data' => $brands,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Brendlarni olishda xatolik yuz berdi',
                'data' => [],
            ];
        }
    }

    /**
     * ID bo'yicha brendni olish
     */
    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $brand = $this->brandRepository->getById($id, $fields);

            return [
                'success' => true,
                'data' => $brand,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Brendni olishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Yangi brend yaratish
     */
    public function store(array $data): array
    {
        try {
            $brand = $this->brandRepository->store($data);

            return [
                'success' => true,
                'data' => $brand,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Brend yaratishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Brendni yangilash
     */
    public function update(Brand $brand, array $data): array
    {
        try {
            $updatedBrand = $this->brandRepository->update($brand, $data);

            return [
                'success' => true,
                'data' => $updatedBrand,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Brendni yangilashda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Brendni o'chirish
     */
    public function delete(Brand $brand): array
    {
        try {
            $this->brandRepository->delete($brand);

            return [
                'success' => true,
                'data' => null,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Brendni o\'chirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Brend holatini o'zgartirish
     */
    public function toggleActive(int $id): array
    {
        try {
            $brand = $this->brandRepository->getById($id);

            if (! $brand) {
                return [
                    'success' => false,
                    'message' => 'Brend topilmadi',
                    'data' => null,
                ];
            }

            $brand->is_active = ! $brand->is_active;
            $brand->save();

            return [
                'success' => true,
                'data' => $brand,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Brend holatini o\'zgartirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }
}
