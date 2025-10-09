<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Modules\Information\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserInterface $userRepository,
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    public function index(array $data)
    {
        try {
            return $this->userRepository->index($data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Foydalanuvchilarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getAll()
    {
        try {
            return $this->userRepository->getAll();
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Barcha foydalanuvchilarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data)
    {
        try {
            $user = $this->userRepository->store($data);

            if (! $user) {
                return [
                    'status' => 'error',
                    'message' => 'Foydalanuvchi qo\'shishda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Foydalanuvchi muvaffaqiyatli qo\'shildi',
                'data' => $user,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Foydalanuvchi qo\'shishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $user = $this->userRepository->getById($id);

            if (! $user) {
                return [
                    'status' => 'error',
                    'message' => 'Foydalanuvchi topilmadi',
                    'status_code' => 404,
                ];
            }

            $updatedUser = $this->userRepository->update($id, $data);

            if (! $updatedUser) {
                return [
                    'status' => 'error',
                    'message' => 'Foydalanuvchi ma\'lumotlarini yangilashda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Foydalanuvchi ma\'lumotlari muvaffaqiyatli yangilandi',
                'data' => $updatedUser,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Foydalanuvchi ma\'lumotlarini yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function updatePassword(array $data)
    {
        try {
            $user = $this->userRepository->getByUsername(Auth::user()->username);

            if (! Hash::check($data['password'], $user->password)) {
                return [
                    'status' => 'error',
                    'message' => 'Joriy parol xato',
                ];
            }

            $this->userRepository->updatePassword($user, $data['new_password']);

            return [
                'status' => 'success',
                'message' => 'Parol muvaffaqiyatli yangilandi',
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Parolni yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id)
    {
        try {
            $user = $this->userRepository->invertActive($id);

            return [
                'status' => 'success',
                'message' => 'Foydalanuvchi faollik holati muvaffaqiyatli o\'zgartirildi',
                'data' => $user,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Foydalanuvchi faolligini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }
}
