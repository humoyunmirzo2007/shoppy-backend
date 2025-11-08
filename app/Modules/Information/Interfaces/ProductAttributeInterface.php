<?php

namespace App\Modules\Information\Interfaces;

use App\Models\ProductAttribute;

interface ProductAttributeInterface
{
    public function store(array $data);

    public function update(ProductAttribute $productAttribute, array $data);
}
