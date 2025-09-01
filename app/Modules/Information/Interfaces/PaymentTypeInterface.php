<?php

namespace App\Modules\Information\Interfaces;

use App\Models\PaymentType;

interface PaymentTypeInterface
{
    public function index(array $data);
    public function getAllActive();

    public function getById(int $id);
    public function store(array $data);
    public function update(PaymentType $paymentType, array $data);
    public function invertActive(int $id);
}
