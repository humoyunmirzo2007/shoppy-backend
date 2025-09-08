<?php

namespace App\Modules\Information\Interfaces;

use App\Models\OtherSource;
use App\Modules\Information\Enums\OtherSourceTypesEnum;
use Illuminate\Database\Eloquent\Collection;

interface OtherSourceInterface
{
    public function getAll(array $data);

    public function getByTypeAllActive(string $type);

    public function create(array $data);

    public function update(OtherSource $otherSource, array $data);

    public function invertActive(OtherSource $otherSource);

    public function findById(int $id, array $fields = ['*']);
}
