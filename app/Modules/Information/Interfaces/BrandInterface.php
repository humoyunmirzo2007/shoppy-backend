<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Brand;

interface BrandInterface
{
    public function getAll(array $data, ?array $fields = ['*']);

    public function getById(int $id, ?array $fields = ['*']);

    public function store(array $data);

    public function update(Brand $brand, array $data);
}
