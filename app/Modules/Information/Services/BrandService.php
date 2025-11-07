<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use App\Modules\Information\Interfaces\BrandInterface;

class BrandService
{
    public function __construct(private BrandInterface $brandRepository) {}

    public function getAll(array $data)
    {
        try {
            $result = $this->brandRepository->getAll($data);

            return [
                'success' => true,
                'message' => 'Brendlar muvaffaqiyatli olindi',
                'data' => $result,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Brendlarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getById(int $id)
    {
        try {
            $brand = $this->brandRepository->getById($id);
            if (! $brand) {
                return [
                    'success' => false,
                    'message' => 'Brend topilmadi',
                ];
            }

            return [
                'success' => true,
                'message' => 'Brend muvaffaqiyatli olindi',
                'data' => $brand,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Brendni olishda xatolik yuz berdi',
            ];
        }
    }

    public function create(array $data)
    {
        try {
            $data['is_active'] = true;
            $brand = $this->brandRepository->create($data);

            return [
                'success' => true,
                'message' => 'Brend muvaffaqiyatli yaratildi',
                'data' => $brand,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Brend yaratishda xatolik yuz berdi',
            ];
        }
    }

    public function update(array $data, int $id)
    {
        try {
            $brand = $this->brandRepository->getById($id);
            if (! $brand) {
                return [
                    'success' => false,
                    'message' => 'Brend topilmadi',
                ];
            }
            unset($data['is_active']);
            $updatedBrand = $this->brandRepository->update($data, $brand);

            return [
                'success' => true,
                'message' => 'Brend muvaffaqiyatli yangilandi',
                'data' => $updatedBrand,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Brendni yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id)
    {
        try {
            $brand = $this->brandRepository->getById($id);
            if (! $brand) {
                return [
                    'success' => false,
                    'message' => 'Brend topilmadi',
                ];
            }
            $updatedBrand = $this->brandRepository->invertActive($brand);

            return [
                'success' => true,
                'message' => 'Brend holati muvaffaqiyatli o\'zgartirildi',
                'data' => $updatedBrand,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Brend holatini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }

    public function allActive()
    {
        try {
            $result = $this->brandRepository->allActive();

            return [
                'success' => true,
                'message' => 'Faol brendlar muvaffaqiyatli olindi',
                'data' => $result,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Faol brendlarni olishda xatolik yuz berdi',
            ];
        }
    }
}
