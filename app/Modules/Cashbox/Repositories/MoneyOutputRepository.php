<?php

namespace App\Modules\Cashbox\Repositories;

use App\Models\MoneyOperation;
use App\Modules\Cashbox\Interfaces\MoneyOutputInterface;

class MoneyOutputRepository implements MoneyOutputInterface
{
    public function __construct(protected MoneyOperation $moneyOperation) {}

    public function getAllMoneyOutputs(array $data = [])
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->moneyOperation->query()
            ->outputs() // faqat output operatsiyalar
            ->with(['user:id,full_name', 'paymentType:id,name', 'costType:id,name', 'client:id,name', 'supplier:id,name'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search)
                            ->orWhere('amount', $search);
                    }
                    $query->orWhere('description', 'ilike', "%$search%")
                        ->orWhereHas('user', fn ($q) => $q->where('full_name', 'ilike', "%$search%"))
                        ->orWhereHas('client', fn ($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('supplier', fn ($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('paymentType', fn ($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('costType', fn ($q) => $q->where('name', 'ilike', "%$search%"));
                });
            })
            ->when(! empty($data['type']), fn ($q) => $q->where('type', $data['type']))
            ->when(! empty($data['client_id']), fn ($q) => $q->where('client_id', $data['client_id']))
            ->when(! empty($data['supplier_id']), fn ($q) => $q->where('supplier_id', $data['supplier_id']))
            ->when(! empty($data['payment_type_id']), fn ($q) => $q->where('payment_type_id', $data['payment_type_id']))
            ->when(! empty($data['cost_type_id']), fn ($q) => $q->where('cost_type_id', $data['cost_type_id']))
            ->when(! empty($data['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $data['date_from']))
            ->when(! empty($data['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $data['date_to']))
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getMoneyOutputById(int $id): ?MoneyOperation
    {
        return $this->moneyOperation
            ->outputs()
            ->with(['user:id,full_name', 'paymentType:id,name', 'costType:id,name', 'client:id,name', 'supplier:id,name'])
            ->find($id);
    }

    public function createMoneyOutput(array $data): MoneyOperation
    {
        $data['operation_type'] = 'output';

        return $this->moneyOperation->create($data);
    }

    public function updateMoneyOutput(int $id, array $data): MoneyOperation
    {
        $moneyOperation = $this->moneyOperation->outputs()->findOrFail($id);
        $moneyOperation->update($data);

        return $moneyOperation->fresh();
    }

    public function deleteMoneyOutput(int $id): bool
    {
        $moneyOperation = $this->moneyOperation->outputs()->findOrFail($id);

        return $moneyOperation->delete();
    }
}
