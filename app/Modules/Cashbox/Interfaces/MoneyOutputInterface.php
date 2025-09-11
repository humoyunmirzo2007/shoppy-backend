<?php

namespace App\Modules\Cashbox\Interfaces;

interface MoneyOutputInterface
{
    public function getAllMoneyOutputs(array $data);
    public function getMoneyOutputById(int $id);
    public function createMoneyOutput(array $data);
    public function updateMoneyOutput(int $id, array $data);
    public function deleteMoneyOutput(int $id);
}
