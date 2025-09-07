<?php

namespace App\Modules\Warehouse\Interfaces;


interface SupplierCalculationInterface
{
    public function getBySupplierId(int $supplierId, array $data);
}
