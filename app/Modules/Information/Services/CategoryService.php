<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Modules\Information\Interfaces\CategoryInterface;

class CategoryService
{
    public function __construct(
        protected CategoryInterface $categoryRepository,
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    public function index(array $data)
    {
        try {
            return $this->categoryRepository->index($data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Kategoriyalarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getAll()
    {
        try {
            return $this->categoryRepository->getAll();
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Barcha kategoriyalarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getAllActive()
    {
        try {
            return $this->categoryRepository->getAllActive();
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Faol kategoriyalarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data)
    {
        try {
            $category = $this->categoryRepository->store($data);

            if (! $category) {
                return [
                    'status' => 'error',
                    'message' => 'Kategoriya qo\'shishda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Kategoriya muvaffaqiyatli qo\'shildi',
                'data' => $category,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Kategoriya qo\'shishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $category = $this->categoryRepository->getById($id);
            if (! $category) {
                return [
                    'status' => 'error',
                    'message' => 'Kategoriya topilmadi',
                    'status_code' => 404,
                ];
            }
            $updatedCategory = $this->categoryRepository->update($category, $data);
            if (! $updatedCategory) {
                return [
                    'status' => 'error',
                    'message' => 'Kategoriya ma\'lumotlarini yangilashda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Kategoriya ma\'lumotlari muvaffaqiyatli yangilandi',
                'data' => $updatedCategory,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Kategoriya ma\'lumotlarini yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id)
    {
        try {
            $category = $this->categoryRepository->invertActive($id);

            return [
                'status' => 'success',
                'message' => 'Kategoriya faolligi muvaffaqiyatli o\'zgartirildi',
                'data' => $category,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Kategoriya faolligini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }
}
