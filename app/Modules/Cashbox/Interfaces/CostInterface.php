<?php

namespace App\Modules\Cashbox\Interfaces;

use App\Models\Cost;
use Illuminate\Database\Eloquent\Collection;

interface CostInterface
{
    public function getAll(array $data = []);
    public function getById(int $id): ?Cost;
    public function store(array $data): Cost;
    public function update(int $id, array $data): Cost;
    public function delete(int $id): bool;
}
