<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use App\Models\Attribute;
use App\Modules\Information\Interfaces\AttributeInterface;

class AttributeService
{
    public function __construct(protected AttributeInterface $attributeRepository) {}

    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $attributes = $this->attributeRepository->getAll($data, $fields);

            return [
                'success' => true,
                'message' => 'Atributlar muvaffaqiyatli olindi',
                'data' => $attributes,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atributlarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $attribute = $this->attributeRepository->getById($id, $fields);

            if (! $attribute) {
                return [
                    'success' => false,
                    'message' => 'Atribut topilmadi',
                ];
            }

            return [
                'success' => true,
                'message' => 'Atribut muvaffaqiyatli olindi',
                'data' => $attribute,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atributni olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data): array
    {
        try {
            $data['is_active'] = true;
            $attribute = $this->attributeRepository->store($data);

            return [
                'success' => true,
                'message' => 'Atribut muvaffaqiyatli yaratildi',
                'data' => $attribute,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atribut yaratishda xatolik yuz berdi',
            ];
        }
    }

    public function update(Attribute $attribute, array $data): array
    {
        try {
            unset($data['is_active']);
            $updatedAttribute = $this->attributeRepository->update($attribute, $data);

            return [
                'success' => true,
                'message' => 'Atribut muvaffaqiyatli yangilandi',
                'data' => $updatedAttribute,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atributni yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id): array
    {
        try {
            $attribute = $this->attributeRepository->getById($id);

            if (! $attribute) {
                return [
                    'success' => false,
                    'message' => 'Atribut topilmadi',
                ];
            }

            $updatedAttribute = $this->attributeRepository->invertActive($attribute);

            return [
                'success' => true,
                'message' => 'Atribut holati muvaffaqiyatli o\'zgartirildi',
                'data' => $updatedAttribute,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Atribut holatini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }
}
