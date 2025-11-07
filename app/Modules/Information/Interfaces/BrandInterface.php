<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Brand;

interface BrandInterface
{
    public function getAll(array $data);

    public function getById(int $id);

    public function create(array $data);

    public function update(array $data, Brand $brand);

    public function invertActive(Brand $brand);

    public function allActive();
}
