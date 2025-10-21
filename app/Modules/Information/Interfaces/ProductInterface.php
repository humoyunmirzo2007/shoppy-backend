<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Product;

interface ProductInterface
{
    public function getAll(array $data, ?array $fields = ['*']);

    public function getById(int $id, ?array $fields = ['*']);

    public function store(array $data);

    public function update(Product $product, array $data);

    public function delete(Product $product);
}
