<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetCategoriesRequest;
use App\Modules\Information\Requests\GetCategoryByIdRequest;
use App\Modules\Information\Requests\StoreCategoryRequest;
use App\Modules\Information\Requests\UpdateCategoryRequest;
use App\Modules\Information\Services\CategoryService;

class CategoryController
{
    public function __construct(protected CategoryService $categoryService) {}

    public function index(GetCategoriesRequest $request)
    {
        $data = $this->categoryService->index($request->validated());

        return DefaultResource::collection($data);
    }

    public function getAll()
    {
        $data = $this->categoryService->getAll();

        return DefaultResource::collection($data);
    }

    public function getAllActive()
    {
        $data = $this->categoryService->getAllActive();

        return DefaultResource::collection($data);
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $this->categoryService->store($request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
            );
        }

        return Response::success(
            $data['data'],
            $data['message'],
            201
        );
    }

    public function update(int $id, UpdateCategoryRequest $request)
    {
        $data = $this->categoryService->update($id, $request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: $data['status_code'] ?? 422
            );
        }

        return Response::success(
            $data['data'],
            $data['message'],
        );
    }

    public function invertActive(GetCategoryByIdRequest $request, int $id)
    {
        $data = $this->categoryService->invertActive($id);

        return Response::success(
            $data['data'],
            $data['message'],
        );
    }
}
