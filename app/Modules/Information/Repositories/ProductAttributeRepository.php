<?php

namespace App\Modules\Information\Repositories;

use App\Models\ProductAttribute;
use App\Modules\Information\Interfaces\ProductAttributeInterface;

class ProductAttributeRepository implements ProductAttributeInterface
{
    public function __construct(protected ProductAttribute $productAttribute) {}

    public function store(array $data): ProductAttribute
    {
        return $this->productAttribute->create($data);
    }

    public function update(ProductAttribute $productAttribute, array $data): ProductAttribute
    {
        $productAttribute->update($data);

        return $productAttribute;
    }
}
