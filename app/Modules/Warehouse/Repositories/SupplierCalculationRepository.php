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
        $limit = $data['limit'] ?? 15;
        $filters = $data['filters'] ?? [];

        return $this->supplierCalculation->query()
            ->select('id', 'supplier_id', 'value', 'type', 'updated_at')
            ->where('supplier_id', $supplierId)
            ->when(!empty($filters['from_date']), function ($query) use ($filters) {
                $from = Carbon::createFromFormat('d.m.Y', $filters['from_date'])->format('Y-m-d');
                $query->whereDate('created_at', '>=', $from);
            })
            ->when(!empty($filters['to_date']), function ($query) use ($filters) {
                $to = Carbon::createFromFormat('d.m.Y', $filters['to_date'])->format('Y-m-d');
                $query->whereDate('created_at', '<=', $to);
            })
            ->orderBy('id', 'desc')
            ->simplePaginate($limit);
    }
}
