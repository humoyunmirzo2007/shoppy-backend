<?php

namespace App\Modules\Warehouse\Services;

use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;

class SupplierCalculationService
{
    public function __construct(
        protected SupplierCalculationInterface $supplierCalculationRepository
    ) {}

    public function getBySupplierId(int $supplierId, array $data)
    {
        return $this->supplierCalculationRepository->getBySupplierId($supplierId, $data);
    }

}
