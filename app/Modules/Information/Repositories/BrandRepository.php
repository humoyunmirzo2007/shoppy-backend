<?php

namespace App\Modules\Information\Repositories;

use App\Models\Brand;
use App\Modules\Information\Interfaces\BrandInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandRepository implements BrandInterface
{
    public function __construct(protected Brand $brand) {}

    /**
     * Barcha brendlarni olish
     */
    public function getAll(array $data, ?array $fields = ['*']): LengthAwarePaginator
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->brand->query()
            ->select($fields)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%$search%");
                });
            })
            ->when(! empty($filters['is_active']), function ($query) use ($filters) {
                $query->where('is_active', $filters['is_active']);
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    /**
     * ID bo'yicha brendni olish
     */
    public function getById(int $id, ?array $fields = ['*']): ?Brand
    {
        return $this->brand->select($fields)->find($id);
    }

    /**
     * Yangi brend yaratish
     */
    public function store(array $data): Brand
    {
        return $this->brand->create($data);
    }

    /**
     * Brendni yangilash
     */
    public function update(Brand $brand, array $data): Brand
    {
        $brand->update($data);

        return $brand->fresh();
    }
}
