<?php

namespace App\Modules\Warehouse\Services;

use App\Helpers\TelegramBugNotifier;
use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;

class SupplierCalculationService
{
    public function __construct(
        protected SupplierCalculationInterface $supplierCalculationRepository,
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    public function getBySupplierId(int $supplierId, array $data)
    {
        try {
            return $this->supplierCalculationRepository->getBySupplierId($supplierId, $data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());
            return [
                'status' => 'error',
                'message' => 'Yetkazib beruvchi hisob-kitoblarini olishda xatolik yuz berdi'
            ];
        }
    }
}
