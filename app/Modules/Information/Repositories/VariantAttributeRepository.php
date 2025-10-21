<?php

namespace App\Modules\Information\Repositories;

use App\Models\VariantAttribute;
use App\Modules\Information\Interfaces\VariantAttributeInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class VariantAttributeRepository implements VariantAttributeInterface
{
    public function __construct(protected VariantAttribute $variantAttribute) {}

    /**
     * Barcha variant atributlarini olish
     */
    public function getAll(array $data, ?array $fields = ['*']): LengthAwarePaginator
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->variantAttribute->query()
            ->select($fields)
            ->with(['variant', 'attributeValue.attribute'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                });
            })
            ->when(! empty($filters['product_variant_id']), function ($query) use ($filters) {
                $query->where('product_variant_id', $filters['product_variant_id']);
            })
            ->when(! empty($filters['attribute_value_id']), function ($query) use ($filters) {
                $query->where('attribute_value_id', $filters['attribute_value_id']);
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    /**
     * ID bo'yicha variant atributini olish
     */
    public function getById(int $id, ?array $fields = ['*']): ?VariantAttribute
    {
        return $this->variantAttribute->select($fields)
            ->with(['variant', 'attributeValue.attribute'])
            ->find($id);
    }

    /**
     * Yangi variant atributini yaratish
     */
    public function store(array $data): VariantAttribute
    {
        return $this->variantAttribute->create($data);
    }

    /**
     * Variant atributini yangilash
     */
    public function update(VariantAttribute $variantAttribute, array $data): VariantAttribute
    {
        $variantAttribute->update($data);

        return $variantAttribute->fresh();
    }

    /**
     * Mahsulot varianti bo'yicha atributlarni o'chirish
     */
    public function deleteByVariant(int $productVariantId): bool
    {
        return $this->variantAttribute->where('product_variant_id', $productVariantId)->delete();
    }
}
