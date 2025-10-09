<?php

namespace App\Modules\Information\Repositories;

use App\Models\Client;
use App\Modules\Information\Interfaces\ClientInterface;

class ClientRepository implements ClientInterface
{
    public function __construct(protected Client $client) {}

    public function getAll(array $data)
    {
        $search = $data['search'] ?? null;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $limit = $data['limit'] ?? 15;

        return $this->client->query()
            ->select('id', 'name', 'phone_number', 'debt', 'is_active')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%{$search}%");
                });
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getAllActive()
    {
        return $this->client->query()
            ->select('id', 'name')
            ->where('is_active', true)
            ->get();
    }

    public function getAllWithDebt(array $data = [])
    {
        $search = $data['search'] ?? null;
        $sort = $data['sort'] ?? ['debt' => 'desc'];
        $limit = $data['limit'] ?? 15;

        return $this->client->query()
            ->select('id', 'name', 'debt')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('name', 'ilike', "%{$search}%");
                });
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getById(int $id)
    {
        return $this->client->find($id);
    }

    public function store(array $data)
    {
        $client = $this->client->create([
            ...$data,
            'is_active' => true,
        ]);

        return $client;
    }

    public function update(Client $client, array $data)
    {
        $client->update($data);

        return $client;
    }

    public function invertActive(int $id)
    {
        $client = $this->client->find($id);
        $client->is_active = ! $client->is_active;
        $client->save();

        return $client;
    }
}
