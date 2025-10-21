<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Models\VariantAttribute;
use App\Modules\Information\Requests\GetVariantAttributeByIdRequest;
use App\Modules\Information\Requests\GetVariantAttributesRequest;
use App\Modules\Information\Requests\StoreVariantAttributeRequest;
use App\Modules\Information\Requests\UpdateVariantAttributeRequest;
use App\Modules\Information\Services\VariantAttributeService;
use Illuminate\Http\JsonResponse;

class VariantAttributeController
{
    public function __construct(protected VariantAttributeService $variantAttributeService) {}

    /**
     * Barcha variant atributlarini olish
     */
    public function index(GetVariantAttributesRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->variantAttributeService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Variant atributlari muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Variant atributlarini olishda xatolik yuz berdi');
    }

    /**
     * ID bo'yicha variant atributini olish
     */
    public function show(GetVariantAttributeByIdRequest $request, int $id): JsonResponse
    {
        $result = $this->variantAttributeService->getById($id, ['*']);

        if ($result['success'] && $result['data']) {
            return Response::success($result['data'], 'Variant atributi muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Variant atributi topilmadi', 404);
    }

    /**
     * Yangi variant atributini yaratish
     */
    public function store(StoreVariantAttributeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->variantAttributeService->store($data);

        if ($result['success']) {
            return Response::success($result['data'], 'Variant atributi muvaffaqiyatli yaratildi', 201);
        }

        return Response::error($result['message'] ?? 'Variant atributini yaratishda xatolik yuz berdi');
    }

    /**
     * Variant atributini yangilash
     */
    public function update(UpdateVariantAttributeRequest $request, VariantAttribute $variantAttribute): JsonResponse
    {
        $data = $request->validated();
        $result = $this->variantAttributeService->update($variantAttribute, $data);

        if ($result['success']) {
            return Response::success($result['data'], 'Variant atributi muvaffaqiyatli yangilandi');
        }

        return Response::error($result['message'] ?? 'Variant atributini yangilashda xatolik yuz berdi');
    }

    /**
     * Mahsulot varianti bo'yicha atributlarni o'chirish
     */
    public function deleteByVariant(int $productVariantId): JsonResponse
    {
        $result = $this->variantAttributeService->deleteByVariant($productVariantId);

        if ($result['success']) {
            return Response::success(null, 'Variant atributlari muvaffaqiyatli o\'chirildi');
        }

        return Response::error($result['message'] ?? 'Variant atributlarini o\'chirishda xatolik yuz berdi');
    }
}
