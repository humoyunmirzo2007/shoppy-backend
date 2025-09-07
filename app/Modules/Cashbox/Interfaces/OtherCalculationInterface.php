<?php

namespace App\Modules\Cashbox\Interfaces;

interface OtherCalculationInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getByPaymentId(int $paymentId);
}
