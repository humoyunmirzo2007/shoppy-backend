<?php

namespace App\Modules\Cashbox\Repositories;

use App\Models\Payment;
use App\Modules\Cashbox\Interfaces\PaymentInterface;
use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository implements PaymentInterface
{
    public function __construct(protected Payment $payment) {}

    public function getAll(array $data = [])
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->payment->query()
            ->with(['user:id,full_name', 'paymentType:id,name', 'client:id,name', 'supplier:id,name'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search)
                            ->orWhere('amount', $search);
                    }
                    $query->orWhere('description', 'ilike', "%$search%")
                        ->orWhereHas('user', fn($q) => $q->where('full_name', 'ilike', "%$search%"))
                        ->orWhereHas('client', fn($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('supplier', fn($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('paymentType', fn($q) => $q->where('name', 'ilike', "%$search%"));
                });
            })
            ->when(!empty($data['type']), fn($q) => $q->where('type', $data['type']))
            ->when(!empty($data['client_id']), fn($q) => $q->where('client_id', $data['client_id']))
            ->when(!empty($data['supplier_id']), fn($q) => $q->where('supplier_id', $data['supplier_id']))
            ->when(!empty($data['payment_type_id']), fn($q) => $q->where('payment_type_id', $data['payment_type_id']))
            ->when(!empty($data['date_from']), fn($q) => $q->whereDate('created_at', '>=', $data['date_from']))
            ->when(!empty($data['date_to']), fn($q) => $q->whereDate('created_at', '<=', $data['date_to']))
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getById(int $id): ?Payment
    {
        return $this->payment->with(['user:id,full_name', 'paymentType:id,name', 'client:id,name', 'supplier:id,name'])->find($id);
    }

    public function store(array $data): Payment
    {
        return $this->payment->create($data);
    }

    public function update(int $id, array $data): Payment
    {
        $payment = $this->payment->findOrFail($id);
        $payment->update($data);
        return $payment->fresh();
    }

    public function delete(int $id): bool
    {
        $payment = $this->payment->findOrFail($id);
        return $payment->delete();
    }

    // Transfer specific methods
    public function getTransfers(array $data = [])
    {
        $data['type'] = PaymentTypesEnum::TRANSFER->value;

        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->payment->query()
            ->with([
                'user:id,full_name',
                'paymentType:id,name',
                'otherPaymentType:id,name'
            ])
            ->where('type', PaymentTypesEnum::TRANSFER->value)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search)
                            ->orWhere('amount', $search);
                    }
                    $query->orWhere('description', 'ilike', "%$search%")
                        ->orWhereHas('user', fn($q) => $q->where('full_name', 'ilike', "%$search%"))
                        ->orWhereHas('paymentType', fn($q) => $q->where('name', 'ilike', "%$search%"))
                        ->orWhereHas('otherPaymentType', fn($q) => $q->where('name', 'ilike', "%$search%"));
                });
            })
            ->when(!empty($data['payment_type_id']), fn($q) => $q->where('payment_type_id', $data['payment_type_id']))
            ->when(!empty($data['other_payment_type_id']), fn($q) => $q->where('other_payment_type_id', $data['other_payment_type_id']))
            ->when(!empty($data['date_from']), fn($q) => $q->whereDate('created_at', '>=', $data['date_from']))
            ->when(!empty($data['date_to']), fn($q) => $q->whereDate('created_at', '<=', $data['date_to']))
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getTransferById(int $id): ?Payment
    {
        return $this->payment
            ->with([
                'user:id,full_name',
                'paymentType:id,name',
                'otherPaymentType:id,name'
            ])
            ->where('type', PaymentTypesEnum::TRANSFER->value)
            ->find($id);
    }
}
