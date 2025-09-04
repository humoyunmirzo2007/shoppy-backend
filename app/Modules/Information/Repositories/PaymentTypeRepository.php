<?php

namespace App\Modules\Information\Repositories;

use App\Models\PaymentType;
use App\Modules\Information\Interfaces\PaymentTypeInterface;

class PaymentTypeRepository implements PaymentTypeInterface
{

    public function __construct(protected PaymentType $paymentType) {}

    public function index(array $data)
    {
        $search = $data['search'] ?? null;
        $limit = $data['limit'] ?? 15;
        $sort = $data['sort'] ?? ['id' => 'desc'];

        return $this->paymentType->query()
            ->select('id', 'name', 'is_active')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%$search%");
                });
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getAllActive()
    {
        return $this->paymentType->query()
            ->select('id', 'name')
            ->where('is_active', true)
            ->get();
    }

    public function getById(int $id)
    {
        return $this->paymentType->find($id);
    }

    public function store(array $data)
    {
        $paymentType = $this->paymentType->create([
            ...$data,
            'is_active' => true,
        ]);

        return $paymentType;
    }

    public function update(PaymentType $paymentType, array $data)
    {
        $paymentType->update($data);

        return $paymentType;
    }

    public function invertActive(int $id)
    {
        $paymentType = $this->paymentType->find($id);
        $paymentType->is_active = !$paymentType->is_active;
        $paymentType->save();

        return $paymentType;
    }
}
