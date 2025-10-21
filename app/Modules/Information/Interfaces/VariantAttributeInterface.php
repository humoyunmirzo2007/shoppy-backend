<?php

namespace App\Modules\Information\Interfaces;

use App\Models\VariantAttribute;

interface VariantAttributeInterface
{
    public function getAll(array $data, ?array $fields = ['*']);

    public function getById(int $id, ?array $fields = ['*']);

    public function store(array $data);

    public function update(VariantAttribute $variantAttribute, array $data);

    public function deleteByVariant(int $productVariantId);
}
