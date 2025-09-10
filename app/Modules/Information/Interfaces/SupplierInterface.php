<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Supplier;

interface SupplierInterface
{
    public function getAll(array $data);
    public function getAllActive();
    public function getById(int $id, array $fields = ['*']);
    public function store(array $data);
    public function update(Supplier $supplier, array $data);
    public function invertActive(int $id);
    public function getAllWithDebt(array $data = []);
}
