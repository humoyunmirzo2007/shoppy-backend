<?php

namespace App\Modules\Information\Repositories;

use App\Models\Product;
use App\Modules\Information\Interfaces\ProductInterface;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductInterface
{

    public function __construct(protected Product $product) {}

    public function getAll(array $data, array $fields = ['*'], ?bool $withLimit = true)
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        $query =  $this->product->query()
            ->select($fields)
            ->with(['category:id,name'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%$search%");
                });
            })
            ->when(!empty($filters['category_id']), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })
            ->sortable($sort);

        if (!$withLimit) {
            return $query->get();
        }
        return $query->simplePaginate($limit);
    }

    public function getById(int $id, array $fields = ['*'])
    {
        $query = $this->product->query()->select($fields);

        // Add category relationship if needed fields include category data
        if (in_array('*', $fields) || in_array('category_id', $fields)) {
            $query->with(['category:id,name']);
        }

        return $query->findOrFail($id);
    }

    public function store(array $data)
    {
        $product = $this->product->create($data);

        return $this->product->with(['category:id,name'])
            ->select('id', 'name', 'unit', 'is_active', 'category_id', 'price')
            ->find($product->id);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);

        return $this->product->with(['category:id,name'])
            ->select('id', 'name', 'unit', 'is_active', 'category_id', 'price')
            ->find($product->id);
    }

    public function invertActive(int $id)
    {
        $product = $this->product->find($id);
        $product->is_active = !$product->is_active;
        $product->save();

        return $this->product->with(['category:id,name'])
            ->select('id', 'name', 'unit', 'is_active', 'category_id', 'price')
            ->find($id);
    }
    public function import(array $insertProducts, array $updateProducts): void
    {
        DB::transaction(function () use ($insertProducts, $updateProducts) {
            if (!empty($insertProducts)) {
                $this->product->insert($insertProducts);
            }

            foreach ($updateProducts as $productData) {
                $product = $this->product->find($productData['id']);
                if ($product) {
                    unset($productData['id']);
                    $product->update($productData);
                }
            }
        });
    }

    public function findByName(string $name): ?Product
    {
        return $this->product->where('name', $name)->first();
    }

    public function getForCheckResidue(array $ids)
    {
        return $this->product
            ->whereIn('id', $ids)
            ->select('id', 'residue')
            ->get()
            ->keyBy('id')
        ;
    }

    public function upsert(array $data, array $uniqueBy, array $updates): void
    {
        $this->product->upsert(
            $data,
            $uniqueBy,
            $updates
        );
    }
}
