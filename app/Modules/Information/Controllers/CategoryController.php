<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Models\Category;
use App\Modules\Information\Requests\GetCategoriesRequest;
use App\Modules\Information\Requests\GetCategoryByIdRequest;
use App\Modules\Information\Requests\StoreCategoryRequest;
use App\Modules\Information\Requests\UpdateCategoryRequest;
use App\Modules\Information\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController
{
    public function __construct(protected CategoryService $categoryService) {}

    /**
     * Barcha kategoriyalarni olish
     */
    public function index(GetCategoriesRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->categoryService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Kategoriyalar muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Kategoriyalarni olishda xatolik yuz berdi');
    }

    /**
     * ID bo'yicha kategoriyani olish
     */
    public function show(GetCategoryByIdRequest $request, int $id): JsonResponse
    {
        $result = $this->categoryService->getById($id, ['*']);

        if ($result['success'] && $result['data']) {
            return Response::success($result['data'], 'Kategoriya muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Kategoriya topilmadi', 404);
    }

    /**
     * ID bo'yicha kategoriyani parent chain bilan olish
     */
    public function showWithParents(GetCategoryByIdRequest $request, int $id): JsonResponse
    {
        $result = $this->categoryService->getByIdWithParents($id, ['*']);

        if ($result['success'] && $result['data']) {
            return Response::success($result['data'], 'Kategoriya parent chain bilan muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Kategoriya topilmadi', 404);
    }

    /**
     * Yangi kategoriya yaratish
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->categoryService->store($data);

        if ($result['success']) {
            return Response::success($result['data'], 'Kategoriya muvaffaqiyatli yaratildi', 201);
        }

        return Response::error($result['message'] ?? 'Kategoriya yaratishda xatolik yuz berdi');
    }

    /**
     * Kategoriyani yangilash
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $data = $request->validated();
        $result = $this->categoryService->update($category, $data);

        if ($result['success']) {
            return Response::success($result['data'], 'Kategoriya muvaffaqiyatli yangilandi');
        }

        return Response::error($result['message'] ?? 'Kategoriya yangilashda xatolik yuz berdi');
    }

    /**
     * Kategoriya faol holatini teskari qilish
     */
    public function toggleActive(int $id): JsonResponse
    {
        $result = $this->categoryService->invertActive($id);

        if ($result['success']) {
            return Response::success($result['data'], 'Kategoriya holati muvaffaqiyatli o\'zgartirildi');
        }

        return Response::error($result['message'] ?? 'Kategoriya topilmadi', 404);
    }

    /**
     * Faol kategoriyalarni olish
     */
    public function active(Request $request): JsonResponse
    {
        $result = $this->categoryService->getActiveCategories(['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Faol kategoriyalar muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Faol kategoriyalarni olishda xatolik yuz berdi');
    }
}
