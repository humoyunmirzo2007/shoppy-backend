<?php

namespace App\Modules\Trade\Repositories;

use App\Models\ClientCalculation;
use App\Modules\Trade\Interfaces\ClientCalculationInterface;
use Carbon\Carbon;

class ClientCalculationRepository implements ClientCalculationInterface
{
    public function __construct(protected ClientCalculation $clientCalculation) {}

    public function getByClientId(int $clientId, array $data)
    {
        $filters = $data['filters'] ?? [];

        return $this->clientCalculation->query()
            ->select('id', 'value', 'type', 'updated_at', 'date')
            ->where('client_id', $clientId)
            ->when(!empty($filters['from_date']), function ($query) use ($filters) {
                $from = Carbon::createFromFormat('d.m.Y', $filters['from_date'])->format('Y-m-d');
                $query->whereDate('date', '>=', $from);
            })
            ->when(!empty($filters['to_date']), function ($query) use ($filters) {
                $to = Carbon::createFromFormat('d.m.Y', $filters['to_date'])->format('Y-m-d');
                $query->whereDate('date', '<=', $to);
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create(array $data)
    {
        return $this->clientCalculation->create($data);
    }

    public function update(int $id, array $data)
    {
        $calculation = $this->clientCalculation->findOrFail($id);
        $calculation->update($data);
        return $calculation->fresh();
    }

    public function delete(int $id)
    {
        $calculation = $this->clientCalculation->findOrFail($id);
        return $calculation->delete();
    }

    public function getByTradeId(int $tradeId)
    {
        return $this->clientCalculation->where('trade_id', $tradeId)->first();
    }

    public function getByPaymentId(int $paymentId)
    {
        return $this->clientCalculation->where('payment_id', $paymentId)->first();
    }
}
