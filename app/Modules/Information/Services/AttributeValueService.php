<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use App\Models\AttributeValue;
use App\Modules\Information\Interfaces\AttributeValueInterface;

class AttributeValueService
{
    public function __construct(protected AttributeValueInterface $attributeValueRepository) {}

    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $attributeValues = $this->attributeValueRepository->getAll($data, $fields);

            return [
                'success' => true,
                'message' => 'Atribut qiymatlari muvaffaqiyatli olindi',
                'data' => $attributeValues,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atribut qiymatlarini olishda xatolik yuz berdi',
            ];
        }
    }

    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $attributeValue = $this->attributeValueRepository->getById($id, $fields);

            if (! $attributeValue) {
                return [
                    'success' => false,
                    'message' => 'Atribut qiymati topilmadi',
                ];
            }

            return [
                'success' => true,
                'message' => 'Atribut qiymati muvaffaqiyatli olindi',
                'data' => $attributeValue,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atribut qiymatini olishda xatolik yuz berdi',
            ];
        }
    }

    public function getByAttributeId(int $attributeId, ?array $fields = ['*']): array
    {
        try {
            $attributeValues = $this->attributeValueRepository->getByAttributeId($attributeId, $fields);

            return [
                'success' => true,
                'message' => 'Atribut qiymatlari muvaffaqiyatli olindi',
                'data' => $attributeValues,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atribut qiymatlarini olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data): array
    {
        try {
            $attributeValue = $this->attributeValueRepository->store($data);

            return [
                'success' => true,
                'message' => 'Atribut qiymati muvaffaqiyatli yaratildi',
                'data' => $attributeValue,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atribut qiymati yaratishda xatolik yuz berdi',
            ];
        }
    }

    public function update(AttributeValue $attributeValue, array $data): array
    {
        try {
            $updatedAttributeValue = $this->attributeValueRepository->update($attributeValue, $data);

            return [
                'success' => true,
                'message' => 'Atribut qiymati muvaffaqiyatli yangilandi',
                'data' => $updatedAttributeValue,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atribut qiymatini yangilashda xatolik yuz berdi',
            ];
        }
    }
}
