<?php

namespace App\Modules\Warehouse\Repositories;

use App\Models\Invoice;
use App\Modules\Warehouse\Interfaces\InvoiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvoiceRepository implements InvoiceInterface
{
    public function __construct(protected Invoice $invoice) {}

    public function getByTypes(array $types, array $data)
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->invoice->query()
            ->select('id', 'date', 'total_price', 'supplier_id', 'other_source_id', 'products_count', 'user_id',  'updated_at')
            ->with([
                'supplier:id,name',
                'otherSource:id,name',
                'user:id,full_name',
            ])
            ->whereIn('type', $types)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('number', 'ilike', "%$search%");
                });
            })
            ->when(!empty($filters['from_date']), function ($query) use ($filters) {
                $from = Carbon::createFromFormat('d.m.Y', $filters['from_date'])->format('Y-m-d');
                $query->whereDate('date', '>=', $from);
            })
            ->when(!empty($filters['to_date']), function ($query) use ($filters) {
                $to = Carbon::createFromFormat('d.m.Y', $filters['to_date'])->format('Y-m-d');
                $query->whereDate('date', '<=', $to);
            })
            ->when(
                !empty($filters['supplier_id']),
                fn($q) =>
                $q->whereHas('supplier', fn($sq) => $sq->where('id', $filters['supplier_id']))
            )
            ->when(
                !empty($filters['user_id']),
                fn($q) =>
                $q->whereHas('user', fn($uq) => $uq->where('id', $filters['user_id']))
            )
            ->when(!empty($filters['price_from']), fn($q) => $q->where('total_price', '>=', $filters['price_from']))
            ->when(!empty($filters['price_to']), fn($q) => $q->where('total_price', '<=', $filters['price_to']))
            ->sortable($sort)
            ->simplePaginate($limit);
    }


    public function getByIdWithProducts(int $id)
    {
        return $this->invoice
            ->select('id', 'supplier_id', 'other_source_id', 'commentary', 'user_id', 'updated_at', 'date', 'type')
            ->with([
                'supplier:id,name',
                'otherSource:id,name',
                'user:id,full_name',
                'invoiceProducts:id,invoice_id,product_id,price,count,total_price',
                'invoiceProducts.product:id,name,category_id,residue',
                'invoiceProducts.product.category:id,name',
            ])
            ->find($id);
    }


    public function findById(int $id)
    {
        return $this->invoice->find($id);
    }

    public function store(array $data)
    {
        $invoice = $this->invoice->create(
            [
                'date' => $data['date'],
                'supplier_id' => $data['supplier_id'] ?? null,
                'other_source_id' => $data['other_source_id'] ?? null,
                'products_count' => abs($data['products_count']),
                'total_price' => abs($data['total_price']),
                'user_id' => Auth::id(),
                'type' => $data['type'],
                'commentary' => $data['commentary'] ?? null
            ]
        );

        return $invoice->load(['supplier:id,name', 'otherSource:id,name', 'user:id,full_name']);
    }

    public function update(Invoice $invoice, array $data)
    {
        $invoice->update($data);

        return $invoice->load(['supplier:id,name', 'otherSource:id,name', 'user:id,full_name']);
    }

    public function delete(Invoice $invoice)
    {
        return $invoice->delete();
    }
}
