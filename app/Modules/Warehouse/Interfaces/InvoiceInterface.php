<?php

namespace App\Modules\Warehouse\Interfaces;

use App\Models\Invoice;

interface InvoiceInterface
{
    public function getByType(string $type, array $data);
    public function getByIdWithProducts(int $id);
    public function store(array $data);
    public function update(Invoice $invoice, array $data);
    public function delete(Invoice $invoice);
    public function findById(int $id);

}
