<?php

namespace App\Modules\Warehouse\Repositories;

use App\Models\InvoiceProduct;
use App\Modules\Warehouse\Interfaces\InvoiceProductInterface;
use Illuminate\Support\Facades\DB;

class InvoiceProductRepository implements InvoiceProductInterface
{
    public function __construct(protected InvoiceProduct $invoiceProduct) {}

    public function store(array $data): bool
    {
        return $this->invoiceProduct->insert($data);
    }

    public function update(array $data)
    {
        return    $this->invoiceProduct->upsert(
            $data,
            ['id'],
            ['invoice_id', 'product_id', 'count', 'price', 'total_price', 'date', 'updated_at']
        );
    }



    public function deleteByIds(array $ids)
    {
        return $this->invoiceProduct->whereIn('id', $ids)->delete();
    }

    public function findMissingIds(int $invoiceId, array $ids): array
    {
        $ids = array_map('intval', $ids);

        return $this->invoiceProduct
            ->where('invoice_id', $invoiceId)
            ->whereIn('id', $ids)
            ->pluck('id')
            ->map(fn($id) => (int)$id)
            ->toArray();
    }


    public function getByInvoiceId(int $invoiceId)
    {
        return $this->invoiceProduct
            ->where('invoice_id', $invoiceId)
            ->get();
    }

    public function getById(int $invoiceId)
    {
        return $this->invoiceProduct
            ->select(['id', 'product_id', 'count'])
            ->where('invoice_id', $invoiceId)
            ->get();
    }
}
