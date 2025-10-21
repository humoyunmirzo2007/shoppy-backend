<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Models\AttributeValue;
use App\Modules\Information\Requests\GetAttributeValueByIdRequest;
use App\Modules\Information\Requests\GetAttributeValuesRequest;
use App\Modules\Information\Requests\StoreAttributeValueRequest;
use App\Modules\Information\Requests\UpdateAttributeValueRequest;
use App\Modules\Information\Services\AttributeValueService;
use Illuminate\Http\JsonResponse;

class AttributeValueController
{
    public function __construct(protected AttributeValueService $attributeValueService) {}

    /**
     * Barcha atribut qiymatlarini olish
     */
    public function index(GetAttributeValuesRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->attributeValueService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Atribut qiymatlari muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Atribut qiymatlarini olishda xatolik yuz berdi');
    }

    /**
     * ID bo'yicha atribut qiymatini olish
     */
    public function show(GetAttributeValueByIdRequest $request, int $id): JsonResponse
    {
        $result = $this->attributeValueService->getById($id, ['*']);

        if ($result['success'] && $result['data']) {
            return Response::success($result['data'], 'Atribut qiymati muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Atribut qiymati topilmadi', 404);
    }

    /**
     * Yangi atribut qiymatini yaratish
     */
    public function store(StoreAttributeValueRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->attributeValueService->store($data);

        if ($result['success']) {
            return Response::success($result['data'], 'Atribut qiymati muvaffaqiyatli yaratildi', 201);
        }

        return Response::error($result['message'] ?? 'Atribut qiymatini yaratishda xatolik yuz berdi');
    }

    /**
     * Atribut qiymatini yangilash
     */
    public function update(UpdateAttributeValueRequest $request, AttributeValue $attributeValue): JsonResponse
    {
        $data = $request->validated();
        $result = $this->attributeValueService->update($attributeValue, $data);

        if ($result['success']) {
            return Response::success($result['data'], 'Atribut qiymati muvaffaqiyatli yangilandi');
        }

        return Response::error($result['message'] ?? 'Atribut qiymatini yangilashda xatolik yuz berdi');
    }

    /**
     * Atribut qiymatini o'chirish
     */
    public function destroy(AttributeValue $attributeValue): JsonResponse
    {
        $result = $this->attributeValueService->delete($attributeValue);

        if ($result['success']) {
            return Response::success(null, 'Atribut qiymati muvaffaqiyatli o\'chirildi');
        }

        return Response::error($result['message'] ?? 'Atribut qiymatini o\'chirishda xatolik yuz berdi');
    }

    /**
     * Atribut bo'yicha qiymatlarni olish
     */
    public function getByAttribute(int $attributeId): JsonResponse
    {
        $data = ['attribute_id' => $attributeId];
        $result = $this->attributeValueService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Atribut qiymatlari muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Atribut qiymatlarini olishda xatolik yuz berdi');
    }

    /**
     * Faol atribut qiymatlarini olish
     */
    public function getAllActive(): JsonResponse
    {
        $data = ['is_active' => true];
        $result = $this->attributeValueService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Faol atribut qiymatlari muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Faol atribut qiymatlarini olishda xatolik yuz berdi');
    }

    /**
     * Atribut qiymati holatini o'zgartirish
     */
    public function toggleActive(int $id): JsonResponse
    {
        $result = $this->attributeValueService->toggleActive($id);

        if ($result['success']) {
            return Response::success($result['data'], 'Atribut qiymati holati muvaffaqiyatli o\'zgartirildi');
        }

        return Response::error($result['message'] ?? 'Atribut qiymati holatini o\'zgartirishda xatolik yuz berdi');
    }
}
