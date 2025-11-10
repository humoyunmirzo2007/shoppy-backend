<?php

namespace App\Modules\Information\Repositories;

use App\Models\Product;
use App\Modules\Information\Interfaces\ProductInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductInterface
{
    public function __construct(protected Product $product) {}

    public function getAll(array $data, ?array $fields = ['*']): LengthAwarePaginator
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 100;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->product->query()
            ->select($fields)
            ->with(['category:id,name_uz,name_ru', 'brand:id,name'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name_uz', 'ilike', "%$search%")
                        ->orWhere('name_ru', 'ilike', "%$search%");
                });
            })
            ->when(! empty($filters['category_id']), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })
            ->when(! empty($filters['brand_id']), function ($query) use ($filters) {
                $query->where('brand_id', $filters['brand_id']);
            })
            ->when(! empty($filters['product_group_id']), function ($query) use ($filters) {
                $query->where('product_group_id', $filters['product_group_id']);
            })
            ->when(! empty($filters['is_active']), function ($query) use ($filters) {
                $query->where('is_active', $filters['is_active']);
            })
            ->when(! empty($filters['price_from']), function ($query) use ($filters) {
                $query->where('price', '>=', $filters['price_from']);
            })
            ->when(! empty($filters['price_to']), function ($query) use ($filters) {
                $query->where('price', '<=', $filters['price_to']);
            })
            ->sortable($sort)
            ->paginate($limit);
    }

    public function getById(int $id, ?array $fields = ['*']): ?Product
    {
        return $this->product->select($fields)
            ->with([
                'category:id,name_uz,name_ru',
                'brand:id,name',
                'productAttributes:id,product_id,attribute_value_id',
                'productAttributes.attributeValue:id,value_uz,value_ru,attribute_id',
                'productAttributes.attributeValue.attribute:id,name_uz,name_ru',
            ])
            ->find($id);
    }

    public function getByIds(array $ids): Collection
    {
        $products = $this->product->whereIn('id', $ids)->get();

        return $products->sortBy(function ($product) use ($ids) {
            return array_search($product->id, $ids);
        })->values();
    }

    public function getByProductGroupId(int $productGroupId, ?array $fields = ['*']): Collection
    {
        return $this->product->select($fields)
            ->where('product_group_id', $productGroupId)
            ->where('is_active', true)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function store(array $data): Product
    {
        return $this->product->create($data);
    }

    public function storeBulk(array $data): array
    {
        $createdProductIds = [];
        foreach ($data as $productData) {
            $product = $this->product->create($productData);
            $createdProductIds[] = $product->id;
        }

        return $this->getByIds($createdProductIds)->all();
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function toggleActive(Product $product): Product
    {
        $product->is_active = ! $product->is_active;
        $product->save();

        return $product;
    }
}
