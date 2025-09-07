<?php

namespace App\Modules\Cashbox\Repositories;

use App\Models\Cost;
use App\Modules\Cashbox\Interfaces\CostInterface;
use Illuminate\Database\Eloquent\Collection;

class CostRepository implements CostInterface
{
    public function __construct(protected Cost $cost) {}

    public function getAll(array $data = [])
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->cost->query()
            ->with(['user:id,full_name', 'costType:id,name', 'client:id,name', 'supplier:id,name'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search)
                            ->orWhere('amount', $search);
                    }
                    $query->orWhere('description', 'ilike', "%$search%")
                        ->orWhereHas('user', fn($q) => $q->where('full_name', 'ilike', "%$search%"))
                        ->orWhereHas('client', fn($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('supplier', fn($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('costType', fn($q) => $q->where('name', 'ilike', "%$search%"));
                });
            })
            ->when(!empty($data['type']), fn($q) => $q->where('type', $data['type']))
            ->when(!empty($data['client_id']), fn($q) => $q->where('client_id', $data['client_id']))
            ->when(!empty($data['supplier_id']), fn($q) => $q->where('supplier_id', $data['supplier_id']))
            ->when(!empty($data['cost_type_id']), fn($q) => $q->where('cost_type_id', $data['cost_type_id']))
            ->when(!empty($data['date_from']), fn($q) => $q->whereDate('created_at', '>=', $data['date_from']))
            ->when(!empty($data['date_to']), fn($q) => $q->whereDate('created_at', '<=', $data['date_to']))
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getById(int $id): ?Cost
    {
        return $this->cost->with(['user:id,full_name', 'costType:id,name', 'client:id,name', 'supplier:id,name'])->find($id);
    }

    public function store(array $data): Cost
    {
        return $this->cost->create($data);
    }

    public function update(int $id, array $data): Cost
    {
        $cost = $this->cost->findOrFail($id);
        $cost->update($data);
        return $cost->fresh();
    }

    public function delete(int $id): bool
    {
        $cost = $this->cost->findOrFail($id);
        return $cost->delete();
    }
}
