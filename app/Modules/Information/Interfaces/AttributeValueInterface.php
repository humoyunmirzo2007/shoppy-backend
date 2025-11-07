<?php

namespace App\Modules\Information\Interfaces;

use App\Models\AttributeValue;

interface AttributeValueInterface
{
    public function getAll(array $data, ?array $fields = ['*']);

    public function getByAttributeId(int $id, ?array $fields = ['*']);

    public function getById(int $id, ?array $fields = ['*']);

    public function store(array $data);

    public function update(AttributeValue $attributeValue, array $data);
}
