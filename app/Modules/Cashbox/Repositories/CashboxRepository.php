<?php

namespace App\Modules\Cashbox\Repositories;

use App\Models\Cashbox;
use App\Modules\Cashbox\Interfaces\CashboxInterface;
use Illuminate\Database\Eloquent\Collection;

class CashboxRepository implements CashboxInterface
{
    public function __construct(protected Cashbox $cashbox) {}

    public function getAll(array $filters = []): Collection
    {
        return $this->cashbox->query()
            ->with(['user', 'paymentType'])
            ->when(!empty($filters['is_active']), fn($q) => $q->where('is_active', $filters['is_active']))
            ->when(!empty($filters['user_id']), fn($q) => $q->where('user_id', $filters['user_id']))
            ->when(!empty($filters['payment_type_id']), fn($q) => $q->where('payment_type_id', $filters['payment_type_id']))
            ->when(!empty($filters['name']), fn($q) => $q->where('name', 'like', '%' . $filters['name'] . '%'))
            ->get();
    }

    public function getById(int $id): ?Cashbox
    {
        return $this->cashbox->with(['user', 'paymentType'])->find($id);
    }

    public function create(array $data): Cashbox
    {
        // Residue is not included in create, it defaults to 0
        $createData = [
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'payment_type_id' => $data['payment_type_id'],
            'is_active' => true, // Default to active when creating
        ];

        return $this->cashbox->create($createData);
    }

    public function toggleActive(int $id): ?Cashbox
    {
        $cashbox = $this->getById($id);

        if (!$cashbox) {
            return null;
        }

        $cashbox->update(['is_active' => !$cashbox->is_active]);

        return $cashbox->fresh(['user', 'paymentType']);
    }
}
