<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Supplier;

interface SupplierInterface
{
    public function getAll(array $data);
    public function getAllActive();
    public function getById(int $id);
    public function store(array $data);
    public function update(Supplier $supplier, array $data);
    public function invertActive(int $id);
}
