<?php

namespace App\Modules\Information\Repositories;

use App\Models\ProductGroup;
use App\Modules\Information\Interfaces\ProductGroupInterface;

class ProductGroupRepository implements ProductGroupInterface
{
    public function __construct(protected ProductGroup $productGroup) {}

    public function getAll(array $data, ?array $fields = ['*'])
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->productGroup->query()
            ->select($fields)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%$search%")
                        ->orWhereHas('brand', function ($q) use ($search) {
                            $q->where('name', 'ilike', "%$search%");
                        });
                });
            })
            ->when(! empty($filters['brand_id']), function ($query) use ($filters) {
                $query->where('brand_id', $filters['brand_id']);
            })
            ->sortable($sort)
            ->paginate($limit);
    }

    public function getById(int $id, ?array $fields = ['*']): ?ProductGroup
    {
        return $this->productGroup->select($fields)
            ->with(['brand:id,name'])
            ->find($id);
    }

    public function store(array $data): ProductGroup
    {
        return $this->productGroup->create($data);
    }

    public function update(ProductGroup $productGroup, array $data): ProductGroup
    {
        $productGroup->update($data);

        return $productGroup->load(['brand:id,name']);
    }
}
