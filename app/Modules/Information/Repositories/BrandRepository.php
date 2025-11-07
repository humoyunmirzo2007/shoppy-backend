<?php

namespace App\Modules\Information\Repositories;

use App\Models\Brand;
use App\Modules\Information\Interfaces\BrandInterface;
use Carbon\Carbon;

class BrandRepository implements BrandInterface
{
    public function __construct(private Brand $brand) {}

    public function getAll(array $data)
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 100;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->brand
            ->select('*')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%$search%");
                });
            })
            ->when(! empty($filters['from_date']), function ($query) use ($filters) {
                $from = Carbon::createFromFormat('d.m.Y', $filters['from_date'])->format('Y-m-d');
                $query->whereDate('created_at', '>=', $from);
            })
            ->when(! empty($filters['to_date']), function ($query) use ($filters) {
                $to = Carbon::createFromFormat('d.m.Y', $filters['to_date'])->format('Y-m-d');
                $query->whereDate('created_at', '<=', $to);
            })
            ->when(! empty($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->sortable($sort)
            ->paginate($limit);
    }

    public function getById(int $id)
    {
        return $this->brand->where('id', $id)->first();
    }

    public function create(array $data)
    {
        return $this->brand->create($data);
    }

    public function update(array $data, Brand $brand)
    {
        $brand->update($data);

        return $brand;
    }

    public function invertActive(Brand $brand)
    {
        $brand->is_active = ! $brand->is_active;
        $brand->save();

        return $brand;
    }

    public function allActive()
    {
        return $this->brand
            ->select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }
}
