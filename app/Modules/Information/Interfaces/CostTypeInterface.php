<?php

namespace App\Modules\Information\Interfaces;

use App\Models\CostType;

interface CostTypeInterface
{
    public function getAll(array $data);
    public function getAllActive();
    public function store(array $data);
    public function update(CostType $brand, array $data);
    public function invertActive(int $id);
}
