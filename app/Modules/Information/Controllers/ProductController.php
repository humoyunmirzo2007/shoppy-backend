<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Models\Product;
use App\Modules\Information\Requests\GetProductByIdRequest;
use App\Modules\Information\Requests\GetProductsRequest;
use App\Modules\Information\Requests\StoreProductRequest;
use App\Modules\Information\Requests\UpdateProductRequest;
use App\Modules\Information\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController
{
    public function __construct(protected ProductService $productService) {}

    /**
     * Barcha mahsulotlarni olish
     */
    public function index(GetProductsRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->productService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulotlar muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Mahsulotlarni olishda xatolik yuz berdi');
    }

    /**
     * ID bo'yicha mahsulotni olish
     */
    public function show(GetProductByIdRequest $request, int $id): JsonResponse
    {
        $result = $this->productService->getById($id, ['*']);

        if ($result['success'] && $result['data']) {
            return Response::success($result['data'], 'Mahsulot muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Mahsulot topilmadi', 404);
    }

    /**
     * Yangi mahsulot yaratish
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->productService->store($data);

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulot muvaffaqiyatli yaratildi', 201);
        }

        return Response::error($result['message'] ?? 'Mahsulot yaratishda xatolik yuz berdi');
    }

    /**
     * Mahsulotni yangilash
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();
        $result = $this->productService->update($product, $data);

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulot muvaffaqiyatli yangilandi');
        }

        return Response::error($result['message'] ?? 'Mahsulotni yangilashda xatolik yuz berdi');
    }

    /**
     * Mahsulotni o'chirish
     */
    public function destroy(Product $product): JsonResponse
    {
        $result = $this->productService->delete($product);

        if ($result['success']) {
            return Response::success(null, 'Mahsulot muvaffaqiyatli o\'chirildi');
        }

        return Response::error($result['message'] ?? 'Mahsulotni o\'chirishda xatolik yuz berdi');
    }

    /**
     * Qoldiq mahsulotlarni olish
     */
    public function getForResidues(): JsonResponse
    {
        $data = ['is_active' => true];
        $result = $this->productService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Qoldiq mahsulotlar muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Qoldiq mahsulotlarni olishda xatolik yuz berdi');
    }

    /**
     * Mahsulot holatini o'zgartirish
     */
    public function invertActive(int $id): JsonResponse
    {
        $result = $this->productService->toggleActive($id);

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulot holati muvaffaqiyatli o\'zgartirildi');
        }

        return Response::error($result['message'] ?? 'Mahsulot holatini o\'zgartirishda xatolik yuz berdi');
    }

    /**
     * Mahsulotlar shablonini yuklab olish
     */
    public function downloadTemplate(): JsonResponse
    {
        $result = $this->productService->downloadTemplate();

        if ($result['success']) {
            return Response::success($result['data'], 'Shablon muvaffaqiyatli yuklab olindi');
        }

        return Response::error($result['message'] ?? 'Shablonni yuklab olishda xatolik yuz berdi');
    }

    /**
     * Mahsulotlarni import qilish
     */
    public function import(): JsonResponse
    {
        $result = $this->productService->import();

        if ($result['success']) {
            return Response::success($result['data'], 'Mahsulotlar muvaffaqiyatli import qilindi');
        }

        return Response::error($result['message'] ?? 'Mahsulotlarni import qilishda xatolik yuz berdi');
    }

    /**
     * Narx yangilash shablonini yuklab olish
     */
    public function downloadUpdatePriceTemplate(): JsonResponse
    {
        $result = $this->productService->downloadUpdatePriceTemplate();

        if ($result['success']) {
            return Response::success($result['data'], 'Narx yangilash shablonini muvaffaqiyatli yuklab olindi');
        }

        return Response::error($result['message'] ?? 'Narx yangilash shablonini yuklab olishda xatolik yuz berdi');
    }

    /**
     * Shablon orqali narxlarni yangilash
     */
    public function updatePricesFromTemplate(): JsonResponse
    {
        $result = $this->productService->updatePricesFromTemplate();

        if ($result['success']) {
            return Response::success($result['data'], 'Narxlar muvaffaqiyatli yangilandi');
        }

        return Response::error($result['message'] ?? 'Narxlarni yangilashda xatolik yuz berdi');
    }
}
