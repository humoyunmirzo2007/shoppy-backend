<?php

namespace App\Modules\Information\Repositories;

use App\Models\AttributeValue;
use App\Modules\Information\Interfaces\AttributeValueInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AttributeValueRepository implements AttributeValueInterface
{
    public function __construct(protected AttributeValue $attributeValue) {}

    public function getAll(array $data, ?array $fields = ['*']): LengthAwarePaginator
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 100;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->attributeValue->query()
            ->select($fields)
            ->with(['attribute:id,name_uz,name_ru'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('value_uz', 'ilike', "%$search%")
                        ->orWhere('value_ru', 'ilike', "%$search%")
                        ->orWhereHas('attribute', function ($q) use ($search) {
                            $q->where('name_uz', 'ilike', "%$search%")
                                ->orWhere('name_ru', 'ilike', "%$search%");
                        });
                });
            })
            ->when(! empty($filters['attribute_id']), function ($query) use ($filters) {
                $query->where('attribute_id', $filters['attribute_id']);
            })
            ->sortable($sort)
            ->paginate($limit);
    }

    public function getById(int $id, ?array $fields = ['*']): ?AttributeValue
    {
        return $this->attributeValue->select($fields)
            ->with(['attribute'])
            ->find($id);
    }

    public function store(array $data): AttributeValue
    {
        return $this->attributeValue->create($data);
    }

    public function update(AttributeValue $attributeValue, array $data): AttributeValue
    {
        $attributeValue->update($data);

        return $attributeValue->fresh();
    }

    public function getByAttributeId(int $id, ?array $fields = ['*'])
    {
        return $this->attributeValue->select($fields)
            ->with(['attribute'])
            ->where('attribute_id', $id)
            ->get();
    }
}
