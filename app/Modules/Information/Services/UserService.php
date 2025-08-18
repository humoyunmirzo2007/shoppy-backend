<?php

namespace App\Modules\Information\Services;

use App\Modules\Information\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(protected UserInterface $userRepository) {}

    public function index(array $data)
    {
        return $this->userRepository->index($data);
    }

    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    public function store(array $data)
    {


        $user = $this->userRepository->store($data);

        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'Foydalanuvchi qo\'shishda xatolik yuz berdi',
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Foydalanuvchi muvaffaqiyatli qo\'shildi',
            'data' => $user
        ];
    }

    public function update(int $id, array $data)
    {
        $user = $this->userRepository->getById($id);

        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'Foydalanuvchi topilmadi',
                'status_code' => 404
            ];
        }


        $updatedUser = $this->userRepository->update($id, $data);

        if (!$updatedUser) {
            return [
                'status' => 'error',
                'message' => 'Foydalanuvchi ma\'lumotlarini yangilashda xatolik yuz berdi',
            ];
        }
        return [
            'status' => 'success',
            'message' => 'Foydalanuvchi ma\'lumotlari muvaffaqiyatli yangilandi',
            'data' => $updatedUser
        ];
    }


    public function updatePassword(array $data)
    {
        $user = $this->userRepository->getByUsername(Auth::user()->username);

        if (!Hash::check($data['password'], $user->password)) {
            return [
                'status' => 'error',
                'message' => 'Joriy parol xato'
            ];
        }

        $this->userRepository->updatePassword($user, $data['new_password']);

        return [
            'status' => 'success',
            'message' => 'Parol muvaffaqiyatli yangilandi'
        ];
    }

    public function invertActive(int $id)
    {
        $user = $this->userRepository->invertActive($id);

        return [
            'status' => 'success',
            'message' => 'Foydalanuvchi faollik holati muvaffaqiyatli o\'zgartirildi',
            'data' => $user
        ];
    }
}
