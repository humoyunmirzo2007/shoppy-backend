<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetProductByIdRequest;
use App\Modules\Information\Requests\GetProductsByGroupIdRequest;
use App\Modules\Information\Requests\GetProductsRequest;
use App\Modules\Information\Requests\StoreProductRequest;
use App\Modules\Information\Requests\UpdateProductRequest;
use App\Modules\Information\Resources\ProductResource;
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
            return Response::success(
                DefaultResource::collection($result['data']),
                'Mahsulotlar muvaffaqiyatli olindi'
            );
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
            return Response::success(
                ProductResource::make($result['data']),
                'Mahsulot muvaffaqiyatli olindi'
            );
        }

        return Response::error($result['message'] ?? 'Mahsulot topilmadi', 404);
    }

    /**
     * Product group ID bo'yicha mahsulotlarni olish
     */
    public function getByProductGroupId(GetProductsByGroupIdRequest $request): JsonResponse
    {
        $productGroupId = $request->validated()['product_group_id'];

        $fields = ['id', 'name_uz', 'name_ru', 'sku', 'price', 'wholesale_price', 'residue', 'is_active', 'images', 'main_image', 'category_id', 'brand_id', 'product_group_id', 'description_uz', 'description_ru'];

        $result = $this->productService->getByProductGroupId($productGroupId, $fields);

        if ($result['success']) {
            return Response::success(
                ProductResource::collection($result['data']),
                'Mahsulotlar muvaffaqiyatli olindi'
            );
        }

        return Response::error($result['message'] ?? 'Mahsulotlarni olishda xatolik yuz berdi');
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
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $result = $this->productService->getById($id, ['*']);

        if (! $result['success'] || ! $result['data']) {
            return Response::error($result['message'] ?? 'Mahsulot topilmadi', 404);
        }

        $product = $result['data'];
        $data = $request->validated();
        $result = $this->productService->update($product, $data);

        if ($result['success']) {
            return Response::success(
                ProductResource::make($result['data']),
                'Mahsulot muvaffaqiyatli yangilandi'
            );
        }

        return Response::error($result['message'] ?? 'Mahsulotni yangilashda xatolik yuz berdi');
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
}
