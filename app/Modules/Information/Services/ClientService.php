<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use App\Modules\Information\Interfaces\ClientInterface;

class ClientService
{
    public function __construct(
        protected ClientInterface $clientRepository
    ) {}

    public function getAll(array $data)
    {
        try {
            $clients = $this->clientRepository->getAll($data);

            return [
                'success' => true,
                'message' => 'Mijozlar muvaffaqiyatli olindi',
                'data' => $clients,
            ];
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Mijozlarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getAllActive()
    {
        try {
            $clients = $this->clientRepository->getAllActive();

            return [
                'success' => true,
                'message' => 'Faol mijozlar muvaffaqiyatli olindi',
                'data' => $clients,
            ];
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Faol mijozlarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getAllWithDebt(array $data = [])
    {
        try {
            $clients = $this->clientRepository->getAllWithDebt($data);

            return [
                'success' => true,
                'message' => 'Qarzli mijozlar muvaffaqiyatli olindi',
                'data' => $clients,
            ];
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Qarzli mijozlarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getByChatId(string $chatId)
    {
        try {
            $client = $this->clientRepository->getByChatId($chatId);

            if (! $client) {
                return [
                    'status' => 'error',
                    'message' => 'Mijoz topilmadi',
                    'status_code' => 404,
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Mijoz muvaffaqiyatli topildi',
                'data' => $client,
            ];
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'status' => 'error',
                'message' => 'Mijozni olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data)
    {
        try {
            $client = $this->clientRepository->store($data);

            if (! $client) {
                return [
                    'status' => 'error',
                    'message' => 'Mijoz qo\'shishda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Mijoz muvaffaqiyatli qo\'shildi',
                'data' => $client,
            ];
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'status' => 'error',
                'message' => 'Mijoz qo\'shishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $client = $this->clientRepository->getById($id);

            if (! $client) {
                return [
                    'status' => 'error',
                    'message' => 'Mijoz topilmadi',
                    'status_code' => 404,
                ];
            }

            $client = $this->clientRepository->update($client, $data);

            if (! $client) {
                return [
                    'status' => 'error',
                    'message' => 'Mijoz ma\'lumotlarini yangilashda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Mijoz ma\'lumotlari muvaffaqiyatli yangilandi',
                'data' => $client,
            ];
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'status' => 'error',
                'message' => 'Mijoz ma\'lumotlarini yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id)
    {
        try {
            $client = $this->clientRepository->invertActive($id);

            return [
                'status' => 'success',
                'message' => 'Mijoz faollik holati muvaffaqiyatli o\'zgartirildi',
                'data' => $client,
            ];
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'status' => 'error',
                'message' => 'Mijoz faolligini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }

    public function getById(int $id)
    {
        try {
            $client = $this->clientRepository->getById($id);

            if (! $client) {
                return [
                    'status' => 'error',
                    'message' => 'Mijoz topilmadi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Mijoz muvaffaqiyatli olindi',
                'data' => $client,
            ];
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'status' => 'error',
                'message' => 'Mijozni olishda xatolik yuz berdi',
            ];
        }
    }

    public function delete(int $id)
    {
        try {
            $client = $this->clientRepository->getById($id);

            if (! $client) {
                return [
                    'status' => 'error',
                    'message' => 'Mijoz topilmadi',
                    'status_code' => 404,
                ];
            }

            $this->clientRepository->delete($client);

            return [
                'status' => 'success',
                'message' => 'Mijoz muvaffaqiyatli o\'chirildi',
                'data' => null,
            ];
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'status' => 'error',
                'message' => 'Mijozni o\'chirishda xatolik yuz berdi',
            ];
        }
    }
}
