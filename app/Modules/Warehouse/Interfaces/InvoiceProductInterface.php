<?php

namespace App\Modules\Warehouse\Interfaces;

interface InvoiceProductInterface
{
    public function store(array $data);

    public function update(array $data);

    public function deleteByIds(array $ids);

    public function findMissingIds(int $invoiceId, array $ids);

    public function getByInvoiceId(int $invoiceId);

    public function getById(int $invoiceId);
}
