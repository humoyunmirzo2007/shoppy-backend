<?php

namespace App\Modules\Cashbox\Repositories;

use App\Models\MoneyOperation;
use App\Modules\Cashbox\Interfaces\MoneyInputInterface;

class MoneyInputRepository implements MoneyInputInterface
{
    public function __construct(protected MoneyOperation $moneyOperation) {}

    public function getAllMoneyInputs(array $data = [])
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->moneyOperation->query()
            ->inputs()
            ->with(['user:id,full_name', 'paymentType:id,name', 'client:id,first_name,middle_name,last_name', 'supplier:id,name'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search)
                            ->orWhere('amount', $search);
                    }
                    $query->orWhere('description', 'ilike', "%$search%")
                        ->orWhereHas('user', fn ($q) => $q->where('full_name', 'ilike', "%$search%"))
                        ->orWhereHas('client', function ($q) use ($search) {
                            $q->where('first_name', 'ilike', "%$search%")
                                ->orWhere('middle_name', 'ilike', "%$search%")
                                ->orWhere('last_name', 'ilike', "%$search%");
                        })
                        ->orWhereHas('supplier', fn ($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('paymentType', fn ($q) => $q->where('name', 'ilike', "%$search%"));
                });
            })
            ->when(! empty($data['type']), fn ($q) => $q->where('type', $data['type']))
            ->when(! empty($data['client_id']), fn ($q) => $q->where('client_id', $data['client_id']))
            ->when(! empty($data['supplier_id']), fn ($q) => $q->where('supplier_id', $data['supplier_id']))
            ->when(! empty($data['payment_type_id']), fn ($q) => $q->where('payment_type_id', $data['payment_type_id']))
            ->when(! empty($data['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $data['date_from']))
            ->when(! empty($data['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $data['date_to']))
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getMoneyInputById(int $id): ?MoneyOperation
    {
        return $this->moneyOperation
            ->inputs()
            ->with(['user:id,full_name', 'paymentType:id,name', 'client:id,first_name,middle_name,last_name', 'supplier:id,name'])
            ->find($id);
    }

    public function createMoneyInput(array $data): MoneyOperation
    {
        $data['operation_type'] = 'input';

        return $this->moneyOperation->create($data);
    }

    public function updateMoneyInput(int $id, array $data): MoneyOperation
    {
        $moneyOperation = $this->moneyOperation->inputs()->findOrFail($id);
        $moneyOperation->update($data);

        return $moneyOperation->fresh();
    }

    public function deleteMoneyInput(int $id): bool
    {
        $moneyOperation = $this->moneyOperation->inputs()->findOrFail($id);

        return $moneyOperation->delete();
    }

    public function getTransfers(array $data = [])
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->moneyOperation->query()
            ->where('type', 'TRANSFER')
            ->with([
                'user:id,full_name',
                'paymentType:id,name',
                'otherPaymentType:id,name',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search)
                            ->orWhere('amount', $search);
                    }
                    $query->orWhere('description', 'ilike', "%$search%")
                        ->orWhereHas('user', fn ($q) => $q->where('full_name', 'ilike', "%$search%"))
                        ->orWhereHas('paymentType', fn ($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('otherPaymentType', fn ($q) => $q->where('name', 'ilike', "%$search%"));
                });
            })
            ->when(! empty($data['payment_type_id']), fn ($q) => $q->where('payment_type_id', $data['payment_type_id']))
            ->when(! empty($data['other_payment_type_id']), fn ($q) => $q->where('other_payment_type_id', $data['other_payment_type_id']))
            ->when(! empty($data['date_from']), fn ($q) => $q->whereDate('created_at', '>=', $data['date_from']))
            ->when(! empty($data['date_to']), fn ($q) => $q->whereDate('created_at', '<=', $data['date_to']))
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getTransferById(int $id): ?\App\Models\MoneyOperation
    {
        return $this->moneyOperation
            ->with([
                'user:id,full_name',
                'paymentType:id,name',
                'otherPaymentType:id,name',
            ])
            ->where('type', 'TRANSFER')
            ->find($id);
    }

    public function createTransfer(array $data): \App\Models\MoneyOperation
    {
        $data['operation_type'] = 'transfer';
        $data['type'] = 'TRANSFER';

        return $this->moneyOperation->create($data);
    }

    public function deleteTransfer(int $id): bool
    {
        $transfer = $this->moneyOperation->where('type', 'TRANSFER')->findOrFail($id);

        return $transfer->delete();
    }
}
