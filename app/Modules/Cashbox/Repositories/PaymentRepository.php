<?php

namespace App\Modules\Cashbox\Repositories;

use App\Models\Payment;
use App\Modules\Cashbox\Interfaces\PaymentInterface;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository implements PaymentInterface
{
    public function __construct(protected Payment $payment) {}

    public function getAll(array $filters = []): Collection
    {
        return $this->payment->query()
            ->with(['cashbox:id,name', 'user:id,full_name', 'paymentType:id,name', 'client:id,name', 'supplier:id,name'])
            ->when(!empty($filters['type']), fn($q) => $q->where('type', $filters['type']))
            ->when(!empty($filters['cashbox_id']), fn($q) => $q->where('cashbox_id', $filters['cashbox_id']))
            ->when(!empty($filters['client_id']), fn($q) => $q->where('client_id', $filters['client_id']))
            ->when(!empty($filters['supplier_id']), fn($q) => $q->where('supplier_id', $filters['supplier_id']))
            ->when(!empty($filters['payment_type_id']), fn($q) => $q->where('payment_type_id', $filters['payment_type_id']))
            ->when(!empty($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(!empty($filters['date_from']), fn($q) => $q->whereDate('created_at', '>=', $filters['date_from']))
            ->when(!empty($filters['date_to']), fn($q) => $q->whereDate('created_at', '<=', $filters['date_to']))
            ->sortable()
            ->get();
    }

    public function getById(int $id): ?Payment
    {
        return $this->payment->with(['cashbox:id,name', 'user:id,full_name', 'paymentType:id,name', 'client:id,name', 'supplier:id,name'])->find($id);
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
}
