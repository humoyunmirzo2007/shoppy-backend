<?php

namespace App\Modules\Information\Interfaces;

use App\Models\Client;

interface ClientInterface
{
    public function getAll(array $data);
    public function getAllActive();
    public function getById(int $id);
    public function store(array $data);
    public function update(Client $client, array $data);
    public function invertActive(int $id);
    public function getAllWithDebt(array $data = []);
}
