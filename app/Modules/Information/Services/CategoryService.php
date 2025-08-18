<?php

namespace App\Modules\Information\Services;

use App\Modules\Information\Interfaces\CategoryInterface;

class CategoryService
{
    public function __construct(protected CategoryInterface $categoryRepository) {}


    public function index(array $data)
    {
        return $this->categoryRepository->index($data);
    }

    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }

    public function getAllActive()
    {
        return $this->categoryRepository->getAllActive();
    }

    public function store(array $data)
    {
        $category = $this->categoryRepository->store($data);

        if (!$category) {
            return [
                'status' => 'error',
                'message' => 'Kategoriya qo\'shishda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Kategoriya muvaffaqiyatli qo\'shildi',
            'data' => $category
        ];
    }

    public function update(int $id, array $data)
    {
        $category = $this->categoryRepository->getById($id);
        if (!$category) {
            return [
                'status' => 'error',
                'message' => 'Kategoriya topilmadi',
                'status_code' => 404
            ];
        }
        $updatedCategory = $this->categoryRepository->update($category, $data);
        if (!$updatedCategory) {
            return [
                'status' => 'error',
                'message' => 'Kategoriya ma\'lumotlarini yangilashda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Kategoriya ma\'lumotlari muvaffaqiyatli yangilandi',
            'data' => $updatedCategory
        ];
    }

    public function invertActive(int $id)
    {
        $category = $this->categoryRepository->invertActive($id);

        return [
            'status' => 'success',
            'message' => 'Kategoriya faolligi muvaffaqiyatli o\'zgartirildi',
            'data' => $category
        ];
    }
}
