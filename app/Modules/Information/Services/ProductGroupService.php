<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use App\Modules\Information\Interfaces\ProductGroupInterface;

class ProductGroupService
{
    public function __construct(private ProductGroupInterface $productGroupRepository) {}

    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $productGroups = $this->productGroupRepository->getAll($data, $fields);

            return [
                'success' => true,
                'message' => 'Mahsulot guruhlari muvaffaqiyatli olindi',
                'data' => $productGroups,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulot guruhlarini olishda xatolik yuz berdi',
            ];
        }
    }

    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $productGroup = $this->productGroupRepository->getById($id, $fields);
            if (! $productGroup) {
                return [
                    'success' => false,
                    'message' => 'Mahsulot guruhi topilmadi',
                ];
            }

            return [
                'success' => true,
                'message' => 'Mahsulot guruhi muvaffaqiyatli olindi',
                'data' => $productGroup,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulot guruhini olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data): array
    {
        try {
            $productGroup = $this->productGroupRepository->store($data);

            return [
                'success' => true,
                'message' => 'Mahsulot guruhi muvaffaqiyatli yaratildi',
                'data' => $productGroup,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulot guruhini yaratishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            $productGroup = $this->productGroupRepository->getById($id);
            if (! $productGroup) {
                return [
                    'success' => false,
                    'message' => 'Mahsulot guruhi topilmadi',
                ];
            }

            $updatedProductGroup = $this->productGroupRepository->update($productGroup, $data);

            return [
                'success' => true,
                'message' => 'Mahsulot guruhi muvaffaqiyatli yangilandi',
                'data' => $updatedProductGroup,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mahsulot guruhini yangilashda xatolik yuz berdi',
            ];
        }
    }
}
