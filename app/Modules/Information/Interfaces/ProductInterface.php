<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductInterface
{
    public function getAll(array $data, ?array $fields = ['*']);

    public function getById(int $id, ?array $fields = ['*']);

    public function getByIds(array $ids): Collection;

    public function getByProductGroupId(int $productGroupId, ?array $fields = ['*']): Collection;

    public function store(array $data);

    public function storeBulk(array $data): array;

    public function update(Product $product, array $data);

    public function delete(Product $product);

    public function toggleActive(Product $product): Product;
}
