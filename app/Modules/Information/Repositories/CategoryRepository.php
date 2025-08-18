<?php

namespace App\Modules\Information\Repositories;

use App\Models\Category;
use App\Modules\Information\Interfaces\CategoryInterface;

class CategoryRepository implements CategoryInterface
{

    public function __construct(protected Category $category) {}

    public function index(array $data)
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->category->query()
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

    public function getAll()
    {
        return $this->category->query()
            ->select('id', 'name')
            ->get();
    }
    public function getAllActive()
    {
        return $this->category->query()
            ->select('id', 'name')
            ->where('is_active', true)
            ->get();
    }

    public function getById(int $id)
    {
        return $this->category->find($id);
    }

    public function store(array $data)
    {
        $category = $this->category->create([
            ...$data,
            'is_active' => true,
        ]);

        return $category;
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);

        return $category;
    }

    public function invertActive(int $id)
    {
        $category = $this->category->find($id);
        $category->is_active = !$category->is_active;
        $category->save();

        return $category;
    }

    public function getByNameOrCreate(string $name)
    {
        return $this->category->firstOrCreate(
            ['name' => $name],
            ['is_active' => true]
        );
    }
}
