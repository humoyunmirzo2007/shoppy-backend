<?php

namespace App\Modules\Information\Repositories;

use App\Models\Attribute;
use App\Modules\Information\Interfaces\AttributeInterface;

class AttributeRepository implements AttributeInterface
{
    public function __construct(private Attribute $attribute) {}

    public function getAll(array $data, ?array $fields = ['*'])
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 100;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->attribute->query()
            ->select($fields)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%$search%")
                        ->orWhere('type', 'ilike', "%$search%");
                });
            })
            ->when(! empty($filters['is_active']), function ($query) use ($filters) {
                $query->where('is_active', $filters['is_active']);
            })
            ->when(! empty($filters['type']), function ($query) use ($filters) {
                $query->where('type', $filters['type']);
            })
            ->sortable($sort)
            ->paginate($limit);
    }

    public function getById(int $id, ?array $fields = ['*']): ?Attribute
    {
        return $this->attribute->select($fields)->find($id);
    }

    public function store(array $data): Attribute
    {
        return $this->attribute->create($data);
    }

    public function update(Attribute $attribute, array $data)
    {
        $attribute->update($data);

        return $attribute->fresh();
    }

    public function invertActive(Attribute $attribute)
    {
        $attribute->is_active = ! $attribute->is_active;

        $attribute->save();

        return $attribute;
    }
}
