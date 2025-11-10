<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetCategoriesRequest;
use App\Modules\Information\Requests\GetCategoryByIdRequest;
use App\Modules\Information\Requests\StoreCategoryRequest;
use App\Modules\Information\Requests\UpdateCategoryRequest;
use App\Modules\Information\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    /**
     * Barcha kategoriyalarni olish
     */
    public function index(GetCategoriesRequest $request)
    {
        $data = $request->validated();

        $result = $this->categoryService->getAll($data, ['*']);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return DefaultResource::collection($result['data']);
    }

    /**
     * ID bo'yicha kategoriyani olish
     */
    public function show(GetCategoryByIdRequest $request, int $id)
    {
        $result = $this->categoryService->getById($id, ['*']);

        if (! $result['success']) {
            return Response::error($result['message'], 404);
        }

        return DefaultResource::make($result['data']);
    }

    /**
     * ID bo'yicha kategoriyani parent chain bilan olish
     */
    public function showWithParents(GetCategoryByIdRequest $request, int $id)
    {
        $result = $this->categoryService->getByIdWithParents($id, ['*']);

        if (! $result['success']) {
            return Response::error($result['message'], 404);
        }

        return DefaultResource::make($result['data']);
    }

    /**
     * Yangi kategoriya yaratish
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $result = $this->categoryService->store($data);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success(message: $result['message'], data: DefaultResource::make($result['data'])->resolve(), status: 201);
    }

    /**
     * Kategoriyani yangilash
     */
    public function update(UpdateCategoryRequest $request, int $id)
    {
        $data = $request->validated();
        $result = $this->categoryService->update($id, $data);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success(message: $result['message'], data: DefaultResource::make($result['data'])->resolve());
    }

    /**
     * Kategoriya faol holatini teskari qilish
     */
    public function toggleActive(int $id)
    {
        $result = $this->categoryService->invertActive($id);

        if (! $result['success']) {
            return Response::error($result['message'], 404);
        }

        return Response::success(message: $result['message'], data: DefaultResource::make($result['data'])->resolve());
    }

    /**
     * Faol kategoriyalarni olish
     */
    public function active(Request $request)
    {
        $result = $this->categoryService->getActiveCategories(['id', 'name_uz', 'name_ru']);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return DefaultResource::collection($result['data']);
    }
}
