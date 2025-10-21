<?php

namespace App\Modules\Information\Repositories;

use App\Models\AttributeValue;
use App\Modules\Information\Interfaces\AttributeValueInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AttributeValueRepository implements AttributeValueInterface
{
    public function __construct(protected AttributeValue $attributeValue) {}

    /**
     * Barcha atribut qiymatlarini olish
     */
    public function getAll(array $data, ?array $fields = ['*']): LengthAwarePaginator
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->attributeValue->query()
            ->select($fields)
            ->with(['attribute'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('value', 'ilike', "%$search%");
                });
            })
            ->when(! empty($filters['attribute_id']), function ($query) use ($filters) {
                $query->where('attribute_id', $filters['attribute_id']);
            })
            ->when(! empty($filters['is_active']), function ($query) use ($filters) {
                $query->where('is_active', $filters['is_active']);
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    /**
     * ID bo'yicha atribut qiymatini olish
     */
    public function getById(int $id, ?array $fields = ['*']): ?AttributeValue
    {
        return $this->attributeValue->select($fields)
            ->with(['attribute'])
            ->find($id);
    }

    /**
     * Yangi atribut qiymatini yaratish
     */
    public function store(array $data): AttributeValue
    {
        return $this->attributeValue->create($data);
    }

    /**
     * Atribut qiymatini yangilash
     */
    public function update(AttributeValue $attributeValue, array $data): AttributeValue
    {
        $attributeValue->update($data);

        return $attributeValue->fresh();
    }
}
