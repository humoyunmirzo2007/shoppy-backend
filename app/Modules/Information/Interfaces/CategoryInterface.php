<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Category;

interface CategoryInterface
{
    public function getAll(array $data, ?array $fields = ['*']);

    public function getById(int $id, ?array $fields = ['*']);

    public function getByIdWithParents(int $id, ?array $fields = ['*']);

    public function store(array $data);

    public function update(Category $category, array $data);

    public function invertActive(int $id);

    public function getActiveCategories(?array $fields = ['*']);
}
