<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Modules\Information\Interfaces\CostTypeInterface;

class CostTypeService
{
    public function __construct(
        protected CostTypeInterface $costTypeRepository,
        protected TelegramBugNotifier $telegramNotifier
    ) {}


    public function getAll(array $data)
    {
        try {
            return $this->costTypeRepository->getAll($data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());
            return [
                'status' => 'error',
                'message' => 'Xarajat turlarini olishda xatolik yuz berdi'
            ];
        }
    }

    public function getAllActive()
    {
        try {
            return $this->costTypeRepository->getAllActive();
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());
            return [
                'status' => 'error',
                'message' => 'Faol xarajat turlarini olishda xatolik yuz berdi'
            ];
        }
    }

    public function store(array $data)
    {
        try {
            $costType = $this->costTypeRepository->store($data);

            if (!$costType) {
                return [
                    'status' => 'error',
                    'message' => 'Xarajat turi qo\'shishda xatolik yuz berdi'
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Xarajat turi muvaffaqiyatli qo\'shildi',
                'data' => $costType
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());
            return [
                'status' => 'error',
                'message' => 'Xarajat turi qo\'shishda xatolik yuz berdi'
            ];
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $costType = $this->costTypeRepository->getById($id);
            if (!$costType) {
                return [
                    'status' => 'error',
                    'message' => 'Xarajat turi topilmadi',
                    'status_code' => 404
                ];
            }
            $costType = $this->costTypeRepository->update($costType, $data);
            if (!$costType) {
                return [
                    'status' => 'error',
                    'message' => 'Xarajat turi ma\'lumotlarini yangilashda xatolik yuz berdi'
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Xarajat turi ma\'lumotlari muvaffaqiyatli yangilandi',
                'data' => $costType
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());
            return [
                'status' => 'error',
                'message' => 'Xarajat turi ma\'lumotlarini yangilashda xatolik yuz berdi'
            ];
        }
    }

    public function invertActive(int $id)
    {
        try {
            $costType = $this->costTypeRepository->invertActive($id);

            return [
                'status' => 'success',
                'message' => 'Xarajat turi faolligi muvaffaqiyatli o\'zgartirildi',
                'data' => $costType
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());
            return [
                'status' => 'error',
                'message' => 'Xarajat turi faolligini o\'zgartirishda xatolik yuz berdi'
            ];
        }
    }
}
