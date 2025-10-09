<?php

namespace App\Modules\Cashbox\Interfaces;

interface MoneyInputInterface
{
    public function getAllMoneyInputs(array $data);

    public function getMoneyInputById(int $id);

    public function createMoneyInput(array $data);

    public function updateMoneyInput(int $id, array $data);

    public function deleteMoneyInput(int $id);

    // Transfer methods
    public function getTransfers(array $data);

    public function getTransferById(int $id);

    public function createTransfer(array $data);

    public function deleteTransfer(int $id);
}
