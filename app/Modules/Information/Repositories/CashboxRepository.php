<?php

namespace App\Modules\Information\Repositories;

use App\Models\Cashbox;
use App\Modules\Information\Interfaces\CashboxInterface;
use Illuminate\Database\Eloquent\Collection;

class CashboxRepository implements CashboxInterface
{
    public function getAll(array $filters = []): Collection
    {
        $query = Cashbox::with(['user', 'paymentType']);

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['payment_type_id'])) {
            $query->where('payment_type_id', $filters['payment_type_id']);
        }

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        return $query->get();
    }

    public function getById(int $id): ?Cashbox
    {
        return Cashbox::with(['user', 'paymentType'])->find($id);
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

        return Cashbox::create($createData);
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
