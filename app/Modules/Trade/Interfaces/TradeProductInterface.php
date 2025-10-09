<?php

namespace App\Modules\Trade\Interfaces;

interface TradeProductInterface
{
    public function store(array $data);

    public function update(array $data);

    public function deleteByIds(array $ids);

    public function findMissingIds(int $tradeId, array $ids);

    public function getByTradeId(int $tradeId);

    public function getById(int $tradeId);
}
