<?php

namespace App\Modules\Information\Interfaces;

use App\Models\User;

interface UserInterface
{
    public function index(array $data);
    public function getAll();
    public function getById(int $id);
    public function getByUsername(string $username);
    public function store(array $data);
    public function update(int $id, array $data);
    public function updatePassword(User $user, string $password);
    public function invertActive(int $id);
}
