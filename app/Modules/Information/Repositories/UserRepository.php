<?php

namespace App\Modules\Information\Repositories;

use App\Models\User;
use App\Modules\Information\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    public function __construct(protected User $user) {}

    public function index(array $data)
    {
        $search = $data['search'] ?? null;
        $sort = $data['sort'] ?? ['id' => 'desc'];
        $limit = $data['limit'] ?? 15;
        $filters = $data['filters'] ?? [];

        return $this->user->query()
            ->select('id', 'full_name', 'position', 'username', 'phone_number', 'is_active')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    }
                    $query->orWhere('full_name', 'ilike', "%{$search}%");
                });
            })
            ->when(! empty($filters['position']), function ($query) use ($filters) {
                $query->where('position', $filters['position']);
            })
            ->sortable($sort)
            ->simplePaginate($limit);
    }

    public function getAll()
    {
        return $this->user->query()
            ->select('id', 'full_name')
            ->get();
    }

    public function getById(int $id)
    {
        return $this->user->find($id);
    }

    public function getByUsername(string $username)
    {
        return $this->user->where('username', $username)->first();
    }

    public function store(array $data)
    {
        $user = $this->user->create([
            ...$data,
            'is_active' => true,
        ]);

        return $user;
    }

    public function update(int $id, array $data)
    {
        $user = $this->user->find($id);
        $user->update($data);

        return $user;
    }

    public function updatePassword(User $user, string $password)
    {
        $user->password = $password;
        $user->save();
    }

    public function invertActive(int $id)
    {
        $user = $this->user->find($id);
        $user->is_active = ! $user->is_active;
        $user->save();

        return $user;
    }
}
