<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Models\Category;
use App\Modules\Information\Interfaces\CategoryInterface;

class CategoryService
{
    public function __construct(protected CategoryInterface $categoryRepository) {}

    /**
     * Barcha kategoriyalarni olish
     */
    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $categories = $this->categoryRepository->getAll($data, $fields);

            return [
                'success' => true,
                'data' => $categories,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Kategoriyalarni olishda xatolik yuz berdi',
                'data' => [],
            ];
        }
    }

    /**
     * ID bo'yicha kategoriyani olish
     */
    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $category = $this->categoryRepository->getById($id, $fields);

            return [
                'success' => true,
                'data' => $category,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Kategoriyani olishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * ID bo'yicha kategoriyani to'liq hierarchical chain bilan olish
     */
    public function getByIdWithParents(int $id, ?array $fields = ['*']): array
    {
        try {
            $category = $this->categoryRepository->getByIdWithParents($id, $fields);

            return [
                'success' => true,
                'data' => $category,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Kategoriyani olishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Yangi kategoriya yaratish
     */
    public function store(array $data): array
    {
        try {
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
                'data' => $category,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Kategoriya yaratishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Kategoriyani yangilash
     */
    public function update(Category $category, array $data): array
    {
        try {
            // Agar parent_id o'zgartirilgan bo'lsa, first_parent_id ni yangilash
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

            $success = $this->categoryRepository->update($category, $data);

            return [
                'success' => $success,
                'data' => $success ? $this->categoryRepository->getById($category->id) : null,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Kategoriya yangilashda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Kategoriya faol holatini teskari qilish
     */
    public function invertActive(int $id): array
    {
        try {
            $success = $this->categoryRepository->invertActive($id);

            return [
                'success' => $success,
                'data' => $success ? $this->categoryRepository->getById($id) : null,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Kategoriya holatini o\'zgartirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Faol kategoriyalarni olish
     */
    public function getActiveCategories(?array $fields = ['*']): array
    {
        try {
            $categories = Category::select($fields)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            return [
                'success' => true,
                'data' => $categories,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Faol kategoriyalarni olishda xatolik yuz berdi',
                'data' => [],
            ];
        }
    }

    /**
     * Kategoriyani o'chirish
     */
    public function delete(Category $category): array
    {
        try {
            $success = $this->categoryRepository->delete($category);

            return [
                'success' => $success,
                'data' => null,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Kategoriyani o\'chirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }
}
