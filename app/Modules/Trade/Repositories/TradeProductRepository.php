<?php

namespace App\Modules\Trade\Repositories;

use App\Models\TradeProduct;
use App\Modules\Trade\Interfaces\TradeProductInterface;

class TradeProductRepository implements TradeProductInterface
{
    public function __construct(protected TradeProduct $tradeProduct) {}

    public function store(array $data): bool
    {
        return $this->tradeProduct->insert($data);
    }

    public function update(array $data)
    {
        return $this->tradeProduct->upsert(
            $data,
            ['id'],
            ['trade_id', 'product_id', 'count', 'price', 'total_price', 'date', 'updated_at']
        );
    }

    public function deleteByIds(array $ids)
    {
        return $this->tradeProduct->whereIn('id', $ids)->delete();
    }

    public function findMissingIds(int $tradeId, array $ids): array
    {
        $ids = array_map('intval', $ids);

        return $this->tradeProduct
            ->where('trade_id', $tradeId)
            ->whereIn('id', $ids)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();
    }

    public function getByTradeId(int $tradeId)
    {
        return $this->tradeProduct
            ->where('trade_id', $tradeId)
            ->get();
    }

    public function getById(int $tradeId)
    {
        return $this->tradeProduct
            ->select(['id', 'product_id', 'count'])
            ->where('trade_id', $tradeId)
            ->get();
    }
}
