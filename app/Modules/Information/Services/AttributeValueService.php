<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Models\AttributeValue;
use App\Modules\Information\Interfaces\AttributeValueInterface;

class AttributeValueService
{
    public function __construct(protected AttributeValueInterface $attributeValueRepository) {}

    /**
     * Barcha atribut qiymatlarini olish
     */
    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $attributeValues = $this->attributeValueRepository->getAll($data, $fields);

            return [
                'success' => true,
                'data' => $attributeValues,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atribut qiymatlarini olishda xatolik yuz berdi',
                'data' => [],
            ];
        }
    }

    /**
     * ID bo'yicha atribut qiymatini olish
     */
    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $attributeValue = $this->attributeValueRepository->getById($id, $fields);

            return [
                'success' => true,
                'data' => $attributeValue,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atribut qiymatini olishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Yangi atribut qiymatini yaratish
     */
    public function store(array $data): array
    {
        try {
            $attributeValue = $this->attributeValueRepository->store($data);

            return [
                'success' => true,
                'data' => $attributeValue,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atribut qiymatini yaratishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Atribut qiymatini yangilash
     */
    public function update(AttributeValue $attributeValue, array $data): array
    {
        try {
            $updatedAttributeValue = $this->attributeValueRepository->update($attributeValue, $data);

            return [
                'success' => true,
                'data' => $updatedAttributeValue,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atribut qiymatini yangilashda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Atribut qiymatini o'chirish
     */
    public function delete(AttributeValue $attributeValue): array
    {
        try {
            $this->attributeValueRepository->delete($attributeValue);

            return [
                'success' => true,
                'data' => null,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atribut qiymatini o\'chirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Atribut qiymati holatini o'zgartirish
     */
    public function toggleActive(int $id): array
    {
        try {
            $attributeValue = $this->attributeValueRepository->getById($id);

            if (! $attributeValue) {
                return [
                    'success' => false,
                    'message' => 'Atribut qiymati topilmadi',
                    'data' => null,
                ];
            }

            $attributeValue->is_active = ! $attributeValue->is_active;
            $attributeValue->save();

            return [
                'success' => true,
                'data' => $attributeValue,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Atribut qiymati holatini o\'zgartirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }
}
