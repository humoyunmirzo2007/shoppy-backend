<?php

namespace App\Modules\Trade\Repositories;

use App\Models\Trade;
use App\Modules\Trade\Interfaces\TradeInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TradeRepository implements TradeInterface
{
    public function __construct(protected Trade $trade) {}

    public function getByType(string $type, array $data)
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $filters = $data['filters'] ?? [];

        return $this->trade->query()
            ->select('id', 'date', 'total_price', 'client_id', 'products_count', 'user_id', 'history', 'updated_at')
            ->with([
                'client:id,name',
                'user:id,full_name',
            ])
            ->where('type', $type)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('number', 'ilike', "%$search%");
                });
            })
            ->when(!empty($filters['from_date']), function ($query) use ($filters) {
                $from = Carbon::createFromFormat('d.m.Y', $filters['from_date'])->format('Y-m-d');
                $query->whereDate('date', '>=', $from);
            })
            ->when(!empty($filters['to_date']), function ($query) use ($filters) {
                $to = Carbon::createFromFormat('d.m.Y', $filters['to_date'])->format('Y-m-d');
                $query->whereDate('date', '<=', $to);
            })
            ->when(
                !empty($filters['client_id']),
                fn($q) =>
                $q->whereHas('client', fn($cq) => $cq->where('id', $filters['client_id']))
            )
            ->when(
                !empty($filters['user_id']),
                fn($q) =>
                $q->whereHas('user', fn($uq) => $uq->where('id', $filters['user_id']))
            )
            ->when(!empty($filters['price_from']), fn($q) => $q->where('total_price', '>=', $filters['price_from']))
            ->when(!empty($filters['price_to']), fn($q) => $q->where('total_price', '<=', $filters['price_to']))
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getByIdWithProducts(int $id)
    {
        return $this->trade
            ->select('id', 'client_id', 'commentary', 'user_id', 'updated_at', 'date', 'type')
            ->with([
                'client:id,name',
                'user:id,full_name',
                'tradeProducts:id,trade_id,product_id,price,count,total_price',
                'tradeProducts.product:id,name,category_id,residue',
                'tradeProducts.product.category:id,name',
            ])
            ->find($id);
    }

    public function findById(int $id)
    {
        return $this->trade->find($id);
    }

    public function store(array $data)
    {
        $trade = $this->trade->create(
            [
                'date' => $data['date'],
                'client_id' => $data['client_id'],
                'products_count' => abs($data['products_count']),
                'total_price' => abs($data['total_price']),
                'user_id' => Auth::id(),
                'type' => $data['type'],
                'commentary' => $data['commentary'] ?? null
            ]
        );

        return $trade->load(['client:id,name', 'user:id,full_name']);
    }

    public function update(Trade $trade, array $data)
    {
        $trade->update($data);

        return $trade->load(['client:id,name', 'user:id,full_name']);
    }

    public function delete(Trade $trade)
    {
        return $trade->delete();
    }
}
