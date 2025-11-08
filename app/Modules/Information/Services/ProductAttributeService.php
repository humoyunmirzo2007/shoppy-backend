<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use App\Models\ProductAttribute;
use App\Modules\Information\Interfaces\ProductAttributeInterface;

class ProductAttributeService
{
    public function __construct(protected ProductAttributeInterface $productAttributeRepository) {}

    public function store(array $data)
    {
        try {
            return $this->productAttributeRepository->store($data);
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Product attribute creation failed',
            ];
        }
    }

    public function update(ProductAttribute $productAttribute, array $data)
    {
        try {
            return $this->productAttributeRepository->update($productAttribute, $data);
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Product attribute update failed',
            ];
        }
    }
}
