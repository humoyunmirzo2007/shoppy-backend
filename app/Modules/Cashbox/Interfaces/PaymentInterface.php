<?php

namespace App\Modules\Cashbox\Interfaces;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

interface PaymentInterface
{
    public function getAll(array $filters = []): Collection;
    public function getById(int $id): ?Payment;
    public function store(array $data): Payment;
    public function update(int $id, array $data): Payment;
    public function delete(int $id): bool;
}
