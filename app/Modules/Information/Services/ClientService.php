<?php

namespace App\Modules\Information\Services;

use App\Modules\Information\Interfaces\ClientInterface;

class ClientService
{
    public function __construct(protected ClientInterface $clientRepository) {}

    public function getAll(array $data)
    {
        return $this->clientRepository->getAll($data);
    }

    public function getAllActive()
    {
        return $this->clientRepository->getAllActive();
    }

    public function getAllWithDebt(array $data = [])
    {
        return $this->clientRepository->getAllWithDebt($data);
    }

    public function store(array $data)
    {
        $client = $this->clientRepository->store($data);

        if (!$client) {
            return [
                'status' => 'error',
                'message' => 'Mijoz qo\'shishda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Mijoz muvaffaqiyatli qo\'shildi',
            'data' => $client
        ];
    }

    public function update(int $id, array $data)
    {
        $client = $this->clientRepository->getById($id);

        if (!$client) {
            return [
                'status' => 'error',
                'message' => 'Mijoz topilmadi',
                'status_code' => 404
            ];
        }

        $client = $this->clientRepository->update($client, $data);

        if (!$client) {
            return [
                'status' => 'error',
                'message' => 'Mijoz ma\'lumotlarini yangilashda xatolik yuz berdi'
            ];
        }
        return [
            'status' => 'success',
            'message' =>  'Mijoz ma\'lumotlari muvaffaqiyatli yangilandi',
            'data' => $client
        ];
    }

    public function invertActive(int $id)
    {
        $client = $this->clientRepository->invertActive($id);

        return [
            'status' => 'success',
            'message' => 'Mijoz faollik holati muvaffaqiyatli o\'zgartirildi',
            'data' => $client
        ];
    }
}
