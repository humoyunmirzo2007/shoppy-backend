<?php

namespace App\Modules\Trade\Interfaces;

interface ClientCalculationInterface
{
    public function getByClientId(int $clientId, array $data);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getByTradeId(int $tradeId);
    public function getByPaymentId(int $paymentId);
}
