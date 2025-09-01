<?php

namespace App\Modules\Trade\Interfaces;

use App\Models\Trade;

interface TradeInterface
{
    public function getByType(string $type, array $data);
    public function getByIdWithProducts(int $id);
    public function store(array $data);
    public function update(Trade $trade, array $data);
    public function delete(Trade $trade);
    public function findById(int $id);
}
