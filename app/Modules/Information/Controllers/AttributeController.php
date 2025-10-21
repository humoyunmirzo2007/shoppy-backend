<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Models\Attribute;
use App\Modules\Information\Requests\GetAttributeByIdRequest;
use App\Modules\Information\Requests\GetAttributesRequest;
use App\Modules\Information\Requests\StoreAttributeRequest;
use App\Modules\Information\Requests\UpdateAttributeRequest;
use App\Modules\Information\Services\AttributeService;
use Illuminate\Http\JsonResponse;

class AttributeController
{
    public function __construct(protected AttributeService $attributeService) {}

    /**
     * Barcha atributlarni olish
     */
    public function index(GetAttributesRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->attributeService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Atributlar muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Atributlarni olishda xatolik yuz berdi');
    }

    /**
     * ID bo'yicha atributni olish
     */
    public function show(GetAttributeByIdRequest $request, int $id): JsonResponse
    {
        $result = $this->attributeService->getById($id, ['*']);

        if ($result['success'] && $result['data']) {
            return Response::success($result['data'], 'Atribut muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Atribut topilmadi', 404);
    }

    /**
     * Yangi atribut yaratish
     */
    public function store(StoreAttributeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $result = $this->attributeService->store($data);

        if ($result['success']) {
            return Response::success($result['data'], 'Atribut muvaffaqiyatli yaratildi', 201);
        }

        return Response::error($result['message'] ?? 'Atribut yaratishda xatolik yuz berdi');
    }

    /**
     * Atributni yangilash
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute): JsonResponse
    {
        $data = $request->validated();
        $result = $this->attributeService->update($attribute, $data);

        if ($result['success']) {
            return Response::success($result['data'], 'Atribut muvaffaqiyatli yangilandi');
        }

        return Response::error($result['message'] ?? 'Atributni yangilashda xatolik yuz berdi');
    }

    /**
     * Atributni o'chirish
     */
    public function destroy(Attribute $attribute): JsonResponse
    {
        $result = $this->attributeService->delete($attribute);

        if ($result['success']) {
            return Response::success(null, 'Atribut muvaffaqiyatli o\'chirildi');
        }

        return Response::error($result['message'] ?? 'Atributni o\'chirishda xatolik yuz berdi');
    }

    /**
     * Faol atributlarni olish
     */
    public function getAllActive(): JsonResponse
    {
        $data = ['is_active' => true];
        $result = $this->attributeService->getAll($data, ['*']);

        if ($result['success']) {
            return Response::success($result['data'], 'Faol atributlar muvaffaqiyatli olindi');
        }

        return Response::error($result['message'] ?? 'Faol atributlarni olishda xatolik yuz berdi');
    }

    /**
     * Atribut holatini o'zgartirish
     */
    public function toggleActive(int $id): JsonResponse
    {
        $result = $this->attributeService->toggleActive($id);

        if ($result['success']) {
            return Response::success($result['data'], 'Atribut holati muvaffaqiyatli o\'zgartirildi');
        }

        return Response::error($result['message'] ?? 'Atribut holatini o\'zgartirishda xatolik yuz berdi');
    }
}
