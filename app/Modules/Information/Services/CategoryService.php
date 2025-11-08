<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use App\Modules\Information\Interfaces\CategoryInterface;

class CategoryService
{
    public function __construct(private CategoryInterface $categoryRepository) {}

    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $categories = $this->categoryRepository->getAll($data, $fields);

            return [
                'success' => true,
                'message' => 'Kategoriyalar muvaffaqiyatli olindi',
                'data' => $categories,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Kategoriyalarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $category = $this->categoryRepository->getById($id, $fields);
            if (! $category) {
                return [
                    'success' => false,
                    'message' => 'Kategoriya topilmadi',
                ];
            }

            return [
                'success' => true,
                'message' => 'Kategoriya muvaffaqiyatli olindi',
                'data' => $category,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Kategoriyani olishda xatolik yuz berdi',
            ];
        }
    }

    public function getByIdWithParents(int $id, ?array $fields = ['*']): array
    {
        try {
            $category = $this->categoryRepository->getByIdWithParents($id, $fields);
            if (! $category) {
                return [
                    'success' => false,
                    'message' => 'Kategoriya topilmadi',
                ];
            }

            return [
                'success' => true,
                'message' => 'Kategoriya parent chain bilan muvaffaqiyatli olindi',
                'data' => $category,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Kategoriyani olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data): array
    {
        try {
            $data['is_active'] = true;
            // Agar parent_id berilgan bo'lsa, first_parent_id ni aniqlash
            if (isset($data['parent_id']) && $data['parent_id']) {
                $parentResult = $this->getById($data['parent_id']);
                if ($parentResult['success'] && $parentResult['data']) {
                    $parent = $parentResult['data'];
                    $data['first_parent_id'] = $parent->first_parent_id ?? $parent->id;
                }
            }

            $category = $this->categoryRepository->store($data);

            return [
                'success' => true,
                'message' => 'Kategoriya muvaffaqiyatli yaratildi',
                'data' => $category,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Kategoriya yaratishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            $category = $this->categoryRepository->getById($id);

            unset($data['is_active']);
            if (isset($data['parent_id'])) {
                if ($data['parent_id']) {
                    $parentResult = $this->getById($data['parent_id']);
                    if ($parentResult['success'] && $parentResult['data']) {
                        $parent = $parentResult['data'];
                        $data['first_parent_id'] = $parent->first_parent_id ?? $parent->id;
                    }
                } else {
                    $data['first_parent_id'] = null;
                }
            }

            $updatedCategory = $this->categoryRepository->update($category, $data);

            return [
                'success' => true,
                'message' => 'Kategoriya muvaffaqiyatli yangilandi',
                'data' => $updatedCategory,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Kategoriya yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id): array
    {
        try {
            $category = $this->categoryRepository->getById($id);
            if (! $category) {
                return [
                    'success' => false,
                    'message' => 'Kategoriya topilmadi',
                ];
            }

            $success = $this->categoryRepository->invertActive($id);
            if (! $success) {
                return [
                    'success' => false,
                    'message' => 'Kategoriya holati o\'zgartirilmadi',
                ];
            }

            $category = $this->categoryRepository->getById($id);

            return [
                'success' => true,
                'message' => 'Kategoriya holati muvaffaqiyatli o\'zgartirildi',
                'data' => $category,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Kategoriya holatini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }

    public function getActiveCategories(?array $fields = ['*']): array
    {
        try {
            $categories = $this->categoryRepository->getActiveCategories($fields);

            return [
                'success' => true,
                'message' => 'Faol kategoriyalar muvaffaqiyatli olindi',
                'data' => $categories,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Faol kategoriyalarni olishda xatolik yuz berdi',
            ];
        }
    }
}
