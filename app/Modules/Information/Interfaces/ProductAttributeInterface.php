<?php

namespace App\Modules\Information\Interfaces;

use App\Models\ProductAttribute;

interface ProductAttributeInterface
{
    public function store(array $data);

    public function storeBulk(array $data): bool;

    public function update(ProductAttribute $productAttribute, array $data);

    public function deleteByProductId(int $productId): bool;
}
