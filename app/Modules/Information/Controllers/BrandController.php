<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Models\Brand;
use App\Modules\Information\Requests\GetBrandByIdRequest;
use App\Modules\Information\Requests\GetBrandsRequest;
use App\Modules\Information\Requests\StoreBrandRequest;
use App\Modules\Information\Requests\UpdateBrandRequest;
use App\Modules\Information\Services\BrandService;
use Illuminate\Http\JsonResponse;

class BrandController
{
    public function __construct(protected BrandService $brandService) {}

    /**
     * Barcha brendlarni olish
     */
    public function index(GetBrandsRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->brandService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Brendlar muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Brendlarni olishda xatolik yuz berdi');
    }

    /**
     * ID bo'yicha brendni olish
     */
    public function show(GetBrandByIdRequest $request, int $id): JsonResponse
    {
        $result = $this->brandService->getById($id, ['*']);

        if ($result['success'] && $result['data']) {
            return Response::success($result['data'], 'Brend muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Brend topilmadi', 404);
    }

    /**
     * Yangi brend yaratish
     */
    public function store(StoreBrandRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->brandService->store($data);

        if ($result['success']) {
            return Response::success($result['data'], 'Brend muvaffaqiyatli yaratildi', 201);
        }

        return Response::error($result['message'] ?? 'Brend yaratishda xatolik yuz berdi');
    }

    /**
     * Brendni yangilash
     */
    public function update(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        $data = $request->validated();
        $result = $this->brandService->update($brand, $data);

        if ($result['success']) {
            return Response::success($result['data'], 'Brend muvaffaqiyatli yangilandi');
        }

        return Response::error($result['message'] ?? 'Brendni yangilashda xatolik yuz berdi');
    }

    /**
     * Brendni o'chirish
     */
    public function destroy(Brand $brand): JsonResponse
    {
        $result = $this->brandService->delete($brand);

        if ($result['success']) {
            return Response::success(null, 'Brend muvaffaqiyatli o\'chirildi');
        }

        return Response::error($result['message'] ?? 'Brendni o\'chirishda xatolik yuz berdi');
    }

    /**
     * Faol brendlarni olish
     */
    public function getAllActive(): JsonResponse
    {
        $data = ['is_active' => true];
        $result = $this->brandService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Faol brendlar muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Faol brendlarni olishda xatolik yuz berdi');
    }

    /**
     * Brend holatini o'zgartirish
     */
    public function toggleActive(int $id): JsonResponse
    {
        $result = $this->brandService->toggleActive($id);

        if ($result['success']) {
            return Response::success($result['data'], 'Brend holati muvaffaqiyatli o\'zgartirildi');
        }

        return Response::error($result['message'] ?? 'Brend holatini o\'zgartirishda xatolik yuz berdi');
    }
}
