<?php

namespace App\Modules\Information\Repositories;

use App\Models\Supplier;
use App\Modules\Information\Interfaces\SupplierInterface;
use Illuminate\Support\Str;

class SupplierRepository implements SupplierInterface
{
    public function __construct(protected Supplier $supplier) {}

    public function getAll(array $data)
    {
        $search = $data['search'] ?? null;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $limit = $data['limit'] ?? 15;

        return $this->supplier->query()
            ->select('id', 'name', 'phone_number', 'address', 'is_active')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%{$search}%");
                });
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getAllActive()
    {

        return $this->supplier->query()
            ->select('id', 'name')
            ->where('is_active', true)
            ->get();
    }

    public function getAllWithDebt()
    {
        return $this->supplier->query()
            ->select('id', 'name', 'debt')
            ->orderBy('debt', 'desc')
            ->get();
    }

    public function getById(int $id, array $fields = ['*'])
    {
        return $this->supplier->select($fields)->find($id);
    }

    public function store(array $data)
    {
        $supplier =  $this->supplier->create([
            ...$data,
            'is_active' => true,
        ]);

        return $supplier;
    }

    public function update(Supplier $supplier, array $data)
    {
        $supplier->update($data);

        return $supplier;
    }



    public function invertActive(int $id)
    {
        $supplier = $this->supplier->find($id);
        $supplier->is_active = !$supplier->is_active;
        $supplier->save();

        return $supplier;
    }
}
