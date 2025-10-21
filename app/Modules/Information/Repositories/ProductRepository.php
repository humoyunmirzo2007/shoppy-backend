<?php

namespace App\Modules\Information\Repositories;

use App\Models\Product;
use App\Modules\Information\Interfaces\ProductInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductInterface
{
    public function __construct(protected Product $product) {}

    /**
     * Barcha mahsulotlarni olish
     */
    public function getAll(array $data, ?array $fields = ['*']): LengthAwarePaginator
    {
        $limit = $data['limit'] ?? 15;
        $page = $data['page'] ?? 1;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        $query = $this->product->select($fields)
            ->with(['category', 'brand']);

        // Qidiruv
        if (isset($data['search']) && ! empty($data['search'])) {
            $search = $data['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        // Kategoriya bo'yicha filtrlash
        if (isset($data['category_id']) && ! empty($data['category_id'])) {
            $query->where('category_id', $data['category_id']);
        }

        // Brend bo'yicha filtrlash
        if (isset($data['brand_id']) && ! empty($data['brand_id'])) {
            $query->where('brand_id', $data['brand_id']);
        }

        return $query->orderBy($sort['field'] ?? 'id', $sort['direction'] ?? 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * ID bo'yicha mahsulotni olish
     */
    public function getById(int $id, ?array $fields = ['*']): ?Product
    {
        return $this->product->select($fields)
            ->with(['category', 'brand', 'variants.attributeValues.attribute'])
            ->find($id);
    }

    /**
     * Yangi mahsulot yaratish
     */
    public function store(array $data): Product
    {
        return $this->product->create($data);
    }

    /**
     * Mahsulotni yangilash
     */
    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh();
    }

    /**
     * Mahsulotni o'chirish
     */
    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
