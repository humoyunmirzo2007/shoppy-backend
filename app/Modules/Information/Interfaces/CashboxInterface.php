<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Cashbox;
use Illuminate\Database\Eloquent\Collection;

interface CashboxInterface
{
    public function getAll(array $filters = []): Collection;

    public function getById(int $id): ?Cashbox;

    public function create(array $data): Cashbox;

    public function update(int $id, array $data): ?Cashbox;

    public function delete(int $id): bool;

    public function toggleActive(int $id): ?Cashbox;
}
