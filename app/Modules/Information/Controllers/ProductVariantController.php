<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Models\ProductVariant;
use App\Modules\Information\Requests\GetProductVariantByIdRequest;
use App\Modules\Information\Requests\GetProductVariantsRequest;
use App\Modules\Information\Requests\StoreProductVariantRequest;
use App\Modules\Information\Requests\UpdateProductVariantRequest;
use App\Modules\Information\Services\ProductVariantService;
use Illuminate\Http\JsonResponse;

class ProductVariantController
{
    public function __construct(protected ProductVariantService $productVariantService) {}

    /**
     * Barcha mahsulot variantlarini olish
     */
    public function index(GetProductVariantsRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->productVariantService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulot variantlari muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Mahsulot variantlarini olishda xatolik yuz berdi');
    }

    /**
     * ID bo'yicha mahsulot variantini olish
     */
    public function show(GetProductVariantByIdRequest $request, int $id): JsonResponse
    {
        $result = $this->productVariantService->getById($id, ['*']);

        if ($result['success'] && $result['data']) {
            return Response::success($result['data'], 'Mahsulot varianti muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Mahsulot varianti topilmadi', 404);
    }

    /**
     * Yangi mahsulot variantini yaratish
     */
    public function store(StoreProductVariantRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->productVariantService->store($data);

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulot varianti muvaffaqiyatli yaratildi', 201);
        }

        return Response::error($result['message'] ?? 'Mahsulot variantini yaratishda xatolik yuz berdi');
    }

    /**
     * Mahsulot variantini yangilash
     */
    public function update(UpdateProductVariantRequest $request, ProductVariant $productVariant): JsonResponse
    {
        $data = $request->validated();
        $result = $this->productVariantService->update($productVariant, $data);

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulot varianti muvaffaqiyatli yangilandi');
        }

        return Response::error($result['message'] ?? 'Mahsulot variantini yangilashda xatolik yuz berdi');
    }

    /**
     * Mahsulot variantini o'chirish
     */
    public function destroy(ProductVariant $productVariant): JsonResponse
    {
        $result = $this->productVariantService->delete($productVariant);

        if ($result['success']) {
            return Response::success(null, 'Mahsulot varianti muvaffaqiyatli o\'chirildi');
        }

        return Response::error($result['message'] ?? 'Mahsulot variantini o\'chirishda xatolik yuz berdi');
    }

    /**
     * Mahsulot bo'yicha variantlarni olish
     */
    public function getByProduct(int $productId): JsonResponse
    {
        $data = ['product_id' => $productId];
        $result = $this->productVariantService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulot variantlari muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Mahsulot variantlarini olishda xatolik yuz berdi');
    }

    /**
     * Faol mahsulot variantlarini olish
     */
    public function getAllActive(): JsonResponse
    {
        $data = ['is_active' => true];
        $result = $this->productVariantService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Faol mahsulot variantlari muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Faol mahsulot variantlarini olishda xatolik yuz berdi');
    }

    /**
     * Mahsulot varianti holatini o'zgartirish
     */
    public function toggleActive(int $id): JsonResponse
    {
        $result = $this->productVariantService->toggleActive($id);

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulot varianti holati muvaffaqiyatli o\'zgartirildi');
        }

        return Response::error($result['message'] ?? 'Mahsulot varianti holatini o\'zgartirishda xatolik yuz berdi');
    }
}
