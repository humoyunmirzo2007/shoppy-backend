<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Product;

interface ProductInterface
{
    public function getAll(array $data);
    public function getById(int $id);
    public function store(array $data);
    public function update(Product $product, array $data);
    public function invertActive(int $id);
    public function import(array $insertProducts, array $updateProducts);
    public function findByName(string $name);
    public function getForCheckResidue(array $ids);
}
