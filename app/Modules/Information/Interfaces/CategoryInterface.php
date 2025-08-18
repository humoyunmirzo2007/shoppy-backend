<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Category;

interface CategoryInterface
{
    public function index(array $data);
    public function getAll();
    public function getAllActive();

    public function getById(int $id);
    public function store(array $data);
    public function update(Category $category, array $data);
    public function invertActive(int $id);
    public function getByNameOrCreate(string $name);
}
