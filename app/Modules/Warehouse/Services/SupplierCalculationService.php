<?php

namespace App\Modules\Warehouse\Services;

use App\Helpers\TelegramBot;
use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;

class SupplierCalculationService
{
    public function __construct(
        protected SupplierCalculationInterface $supplierCalculationRepository
    ) {}

    public function getBySupplierId(int $supplierId, array $data)
    {
        try {
            return $this->supplierCalculationRepository->getBySupplierId($supplierId, $data);
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'status' => 'error',
                'message' => 'Yetkazib beruvchi hisob-kitoblarini olishda xatolik yuz berdi',
            ];
        }
    }
}
