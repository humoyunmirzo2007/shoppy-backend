<?php

namespace App\Modules\Information\Repositories;

use App\Models\ProductVariant;
use App\Modules\Information\Interfaces\ProductVariantInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductVariantRepository implements ProductVariantInterface
{
    public function __construct(protected ProductVariant $productVariant) {}

    /**
     * Barcha mahsulot variantlarini olish
     */
    public function getAll(array $data, ?array $fields = ['*']): LengthAwarePaginator
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->productVariant->query()
            ->select($fields)
            ->with(['product', 'attributeValues.attribute'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('sku', 'ilike', "%$search%");
                });
            })
            ->when(! empty($filters['product_id']), function ($query) use ($filters) {
                $query->where('product_id', $filters['product_id']);
            })
            ->when(! empty($filters['is_active']), function ($query) use ($filters) {
                $query->where('is_active', $filters['is_active']);
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    /**
     * ID bo'yicha mahsulot variantini olish
     */
    public function getById(int $id, ?array $fields = ['*']): ?ProductVariant
    {
        return $this->productVariant->select($fields)
            ->with(['product', 'attributeValues.attribute'])
            ->find($id);
    }

    /**
     * Yangi mahsulot variantini yaratish
     */
    public function store(array $data): ProductVariant
    {
        return $this->productVariant->create($data);
    }

    /**
     * Mahsulot variantini yangilash
     */
    public function update(ProductVariant $productVariant, array $data): ProductVariant
    {
        $productVariant->update($data);

        return $productVariant->fresh();
    }
}
