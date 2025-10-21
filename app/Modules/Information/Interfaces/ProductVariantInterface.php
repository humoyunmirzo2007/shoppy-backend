<?php

namespace App\Modules\Information\Interfaces;

use App\Models\ProductVariant;

interface ProductVariantInterface
{
    public function getAll(array $data, ?array $fields = ['*']);

    public function getById(int $id, ?array $fields = ['*']);

    public function store(array $data);

    public function update(ProductVariant $productVariant, array $data);
}
