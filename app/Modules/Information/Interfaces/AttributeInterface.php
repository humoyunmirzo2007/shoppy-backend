<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Attribute;

interface AttributeInterface
{
    public function getAll(array $data, ?array $fields = ['*']);

    public function getById(int $id, ?array $fields = ['*']);

    public function store(array $data);

    public function update(Attribute $attribute, array $data);

    public function invertActive(Attribute $attribute);

    public function allActive();
}
