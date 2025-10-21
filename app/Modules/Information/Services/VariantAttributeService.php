<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Models\VariantAttribute;
use App\Modules\Information\Interfaces\VariantAttributeInterface;

class VariantAttributeService
{
    public function __construct(protected VariantAttributeInterface $variantAttributeRepository) {}

    /**
     * Barcha variant atributlarini olish
     */
    public function getAll(array $data, ?array $fields = ['*']): array
    {
        try {
            $variantAttributes = $this->variantAttributeRepository->getAll($data, $fields);

            return [
                'success' => true,
                'data' => $variantAttributes,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Variant atributlarini olishda xatolik yuz berdi',
                'data' => [],
            ];
        }
    }

    /**
     * ID bo'yicha variant atributini olish
     */
    public function getById(int $id, ?array $fields = ['*']): array
    {
        try {
            $variantAttribute = $this->variantAttributeRepository->getById($id, $fields);

            return [
                'success' => true,
                'data' => $variantAttribute,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Variant atributini olishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Yangi variant atributini yaratish
     */
    public function store(array $data): array
    {
        try {
            $variantAttribute = $this->variantAttributeRepository->store($data);

            return [
                'success' => true,
                'data' => $variantAttribute,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Variant atributini yaratishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Variant atributini yangilash
     */
    public function update(VariantAttribute $variantAttribute, array $data): array
    {
        try {
            $updatedVariantAttribute = $this->variantAttributeRepository->update($variantAttribute, $data);

            return [
                'success' => true,
                'data' => $updatedVariantAttribute,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Variant atributini yangilashda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Variant atributini o'chirish
     */
    public function delete(VariantAttribute $variantAttribute): array
    {
        try {
            $this->variantAttributeRepository->delete($variantAttribute);

            return [
                'success' => true,
                'data' => null,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Variant atributini o\'chirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }

    /**
     * Mahsulot varianti bo'yicha atributlarni o'chirish
     */
    public function deleteByVariant(int $productVariantId): array
    {
        try {
            $this->variantAttributeRepository->deleteByVariant($productVariantId);

            return [
                'success' => true,
                'data' => null,
            ];
        } catch (\Exception $e) {
            TelegramBugNotifier::sendError($e, request());

            return [
                'success' => false,
                'message' => 'Variant atributlarini o\'chirishda xatolik yuz berdi',
                'data' => null,
            ];
        }
    }
}
