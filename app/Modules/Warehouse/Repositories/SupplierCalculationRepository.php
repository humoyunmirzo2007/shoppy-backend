<?php

namespace App\Modules\Warehouse\Repositories;

use App\Models\SupplierCalculation;
use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;
use Carbon\Carbon;

class SupplierCalculationRepository implements SupplierCalculationInterface
{
    public function __construct(protected SupplierCalculation $supplierCalculation) {}

    public function getBySupplierId(int $supplierId, array $data)
    {
        $filters = $data['filters'] ?? [];

        return $this->supplierCalculation->query()
            ->select('id', 'supplier_id', 'value', 'type', 'updated_at', 'date')
            ->where('supplier_id', $supplierId)
            ->when(! empty($filters['from_date']), function ($query) use ($filters) {
                $from = Carbon::createFromFormat('d.m.Y', $filters['from_date'])->format('Y-m-d');
                $query->whereDate('date', '>=', $from);
            })
            ->when(! empty($filters['to_date']), function ($query) use ($filters) {
                $to = Carbon::createFromFormat('d.m.Y', $filters['to_date'])->format('Y-m-d');
                $query->whereDate('date', '<=', $to);
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create(array $data)
    {
        return $this->supplierCalculation->create($data);
    }

    public function update(int $id, array $data)
    {
        $calculation = $this->supplierCalculation->findOrFail($id);
        $calculation->update($data);

        return $calculation->fresh();
    }

    public function delete(int $id)
    {
        $calculation = $this->supplierCalculation->findOrFail($id);

        return $calculation->delete();
    }

    public function getByInvoiceId(int $invoiceId)
    {
        return $this->supplierCalculation->where('invoice_id', $invoiceId)->first();
    }

    public function getByPaymentId(int $paymentId)
    {
        return $this->supplierCalculation->where('payment_id', $paymentId)->first();
    }
}
