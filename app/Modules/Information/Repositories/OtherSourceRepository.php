<?php

namespace App\Modules\Information\Repositories;

use App\Models\OtherSource;
use App\Modules\Information\Interfaces\OtherSourceInterface;

class OtherSourceRepository implements OtherSourceInterface
{
    public function __construct(protected OtherSource $otherSource) {}

    public function getAll(array $data)
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->otherSource->query()
            ->select('id', 'name', 'is_active', 'type')
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

    public function getByTypeAllActive(string $type)
    {
        return $this->otherSource
            ->where('is_active', true)
            ->where('type', $type)
            ->get();
    }

    public function create(array $data)
    {
        return $this->otherSource->create($data);
    }

    public function update(OtherSource $otherSource, array $data)
    {
        $otherSource->update($data);
        return $otherSource;
    }

    public function invertActive(OtherSource $otherSource)
    {
        $otherSource->update(['is_active' => !$otherSource->is_active]);
        return $otherSource;
    }

    public function findById(int $id)
    {
        return $this->otherSource->find($id);
    }
}
