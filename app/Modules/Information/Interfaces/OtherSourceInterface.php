<?php

namespace App\Modules\Information\Interfaces;

use App\Models\OtherSource;

interface OtherSourceInterface
{
    public function getAll(array $data);

    public function getByTypeAllActive(string $type);

    public function create(array $data);

    public function update(OtherSource $otherSource, array $data);

    public function invertActive(OtherSource $otherSource);

    public function findById(int $id, array $fields = ['*']);
}
