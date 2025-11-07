<?php

namespace App\Modules\Information\Repositories;

use App\Models\Category;
use App\Modules\Information\Interfaces\CategoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryInterface
{
    public function __construct(protected Category $category) {}

    public function getAll(array $data, ?array $fields = ['*']): LengthAwarePaginator
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->category->query()
            ->select($fields)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%$search%")
                        ->orWhere('description', 'ilike', "%$search%");
                });
            })
            ->when(! empty($filters['is_active']), function ($query) use ($filters) {
                $query->where('is_active', $filters['is_active']);
            })
            ->when(! empty($filters['parent_id']), function ($query) use ($filters) {
                $query->where('parent_id', $filters['parent_id']);
            })
            ->when(! empty($filters['first_parent_id']), function ($query) use ($filters) {
                $query->where('first_parent_id', $filters['first_parent_id']);
            })
            ->sortable($sort)
            ->paginate($limit);
    }

    public function getById(int $id, ?array $fields = ['*']): ?Category
    {
        return $this->category->select($fields)->find($id);
    }

    public function getByIdWithParents(int $id, ?array $fields = ['*']): ?Category
    {
        $category = $this->category->select($fields)->find($id);

        if (! $category) {
            return null;
        }

        if ($category->first_parent_id) {
            $firstParentWithChildren = $this->getCategoryWithAllChildren($category->first_parent_id, $fields);

            if ($firstParentWithChildren) {
                return $firstParentWithChildren;
            }
        }

        return $category;
    }

    private function getCategoryWithAllChildren(int $categoryId, ?array $fields = ['*']): ?Category
    {
        $category = $this->category->select($fields)->find($categoryId);

        if (! $category) {
            return null;
        }

        $children = $this->category->select($fields)
            ->where('parent_id', $categoryId)
            ->orderBy('sort_order')
            ->get();

        $childrenWithSubChildren = $children->map(function ($child) use ($fields) {
            $childWithChildren = $this->getCategoryWithAllChildren($child->id, $fields);

            return $childWithChildren ?: $child;
        });

        $category->setAttribute('children', $childrenWithSubChildren);

        return $category;
    }

    public function store(array $data): Category
    {
        return $this->category->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->fresh();
    }

    public function invertActive(int $id): bool
    {
        $category = $this->getById($id);
        if (! $category) {
            return false;
        }

        return $category->update(['is_active' => ! $category->is_active]);
    }

    public function getActiveCategories(?array $fields = ['*']): Collection
    {
        return $this->category
            ->select($fields)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
