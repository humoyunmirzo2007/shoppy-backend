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
            return $this->brandRepository->getAll($data);
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return null;
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
            return $this->brandRepository->allActive();

        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return null;
        }
    }
}
