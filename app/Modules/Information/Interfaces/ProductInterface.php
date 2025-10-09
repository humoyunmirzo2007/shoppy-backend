<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Product;

interface ProductInterface
{
    public function getAll(array $data, array $fields = ['*'], ?bool $withLimit = true);

    public function getById(int $id, array $fields = ['*']);

    public function store(array $data);

    public function update(Product $product, array $data);

    public function invertActive(int $id);

    public function import(array $insertProducts, array $updateProducts);

    public function findByName(string $name);

    public function getForCheckResidue(array $ids);

    public function upsert(array $data, array $uniqueBy, array $updates);
}
