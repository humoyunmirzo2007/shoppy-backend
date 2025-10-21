<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Models\Attribute;
use App\Modules\Information\Interfaces\AttributeInterface;

class AttributeService
{
    public function __construct(protected AttributeInterface $attributeRepository) {}

    /**
     * Barcha atributlarni olish
     */
    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $attributes = $this->attributeRepository->getAll($data, $fields);

            return [
                'success' => true,
                'data' => $attributes,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atributlarni olishda xatolik yuz berdi',
                'data' => [],
            ];
        }
    }

    /**
     * ID bo'yicha atributni olish
     */
    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $attribute = $this->attributeRepository->getById($id, $fields);

            return [
                'success' => true,
                'data' => $attribute,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atributni olishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Yangi atribut yaratish
     */
    public function store(array $data): array
    {
        try {
            $attribute = $this->attributeRepository->store($data);

            return [
                'success' => true,
                'data' => $attribute,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atribut yaratishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Atributni yangilash
     */
    public function update(Attribute $attribute, array $data): array
    {
        try {
            $updatedAttribute = $this->attributeRepository->update($attribute, $data);

            return [
                'success' => true,
                'data' => $updatedAttribute,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atributni yangilashda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Atributni o'chirish
     */
    public function delete(Attribute $attribute): array
    {
        try {
            $this->attributeRepository->delete($attribute);

            return [
                'success' => true,
                'data' => null,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atributni o\'chirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Atribut holatini o'zgartirish
     */
    public function toggleActive(int $id): array
    {
        try {
            $attribute = $this->attributeRepository->getById($id);

            if (! $attribute) {
                return [
                    'success' => false,
                    'message' => 'Atribut topilmadi',
                    'data' => null,
                ];
            }

            $attribute->is_active = ! $attribute->is_active;
            $attribute->save();

            return [
                'success' => true,
                'data' => $attribute,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atribut holatini o\'zgartirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }
}
