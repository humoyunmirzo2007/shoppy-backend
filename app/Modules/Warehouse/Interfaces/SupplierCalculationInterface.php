<?php

namespace App\Modules\Warehouse\Interfaces;


interface SupplierCalculationInterface
{
    public function getBySupplierId(int $supplierId, array $data);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getByInvoiceId(int $invoiceId);
}
