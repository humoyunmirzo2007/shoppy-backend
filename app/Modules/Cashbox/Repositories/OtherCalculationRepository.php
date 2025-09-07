<?php

namespace App\Modules\Cashbox\Repositories;

use App\Models\OtherCalculation;
use App\Modules\Cashbox\Interfaces\OtherCalculationInterface;

class OtherCalculationRepository implements OtherCalculationInterface
{
    public function __construct(protected OtherCalculation $otherCalculation) {}

    public function create(array $data)
    {
        return $this->otherCalculation->create($data);
    }

    public function update(int $id, array $data)
    {
        $calculation = $this->otherCalculation->findOrFail($id);
        $calculation->update($data);
        return $calculation->fresh();
    }

    public function delete(int $id)
    {
        $calculation = $this->otherCalculation->findOrFail($id);
        return $calculation->delete();
    }

    public function getByPaymentId(int $paymentId)
    {
        return $this->otherCalculation->where('payment_id', $paymentId)->first();
    }
}
