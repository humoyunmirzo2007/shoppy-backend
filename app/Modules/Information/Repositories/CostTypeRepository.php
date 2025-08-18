<?php

namespace App\Modules\Information\Repositories;

use App\Models\CostType;
use App\Modules\Information\Interfaces\CostTypeInterface;

class CostTypeRepository implements CostTypeInterface
{

    public function __construct(protected CostType $costType) {}

    public function getAll(array $data)
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->costType->query()
            ->select('id', 'name', 'is_active')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%$search%");
                });
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getAllActive()
    {
        return $this->costType->query()
            ->select('id', 'name')
            ->where('is_active', true)
            ->get();
    }

    public function getById(int $id)
    {
        return $this->costType->find($id);
    }

    public function store(array $data)
    {
        $costType =  $this->costType->create([
            'name' => $data['name'],
            'is_active' =>  true,
        ]);

        return $costType;
    }

    public function update(CostType $costType, array $data)
    {
        $costType->update($data);

        return $costType;
    }

    public function invertActive(int $id)
    {
        $costType = $this->costType->find($id);
        $costType->is_active = !$costType->is_active;
        $costType->save();

        return $costType;
    }

    public function getByNameOrCreate(string $name)
    {
        return $this->costType->firstOrCreate(
            ['name' => $name],
            ['is_active' => true]
        );
    }
}
