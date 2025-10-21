<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Modules\Information\Interfaces\SupplierInterface;

class SupplierService
{
    public function __construct(
        protected SupplierInterface $supplierRepository,
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    public function getAll(array $data)
    {
        try {
            return $this->supplierRepository->getAll($data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Ta\'minotchilarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getAllActive()
    {
        try {
            return $this->supplierRepository->getAllActive();
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Faol ta\'minotchilarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getAllWithDebt(array $data = [])
    {
        try {
            return $this->supplierRepository->getAllWithDebt($data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Qarzli ta\'minotchilarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data)
    {
        try {
            $supplier = $this->supplierRepository->store($data);

            if (! $supplier) {
                return [
                    'status' => 'error',
                    'message' => 'Ta\'minotchi qo\'shishda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Ta\'minotchi muvaffaqiyatli qo\'shildi',
                'data' => $supplier,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Ta\'minotchi qo\'shishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $supplier = $this->supplierRepository->getById($id);

            if (! $supplier) {
                return [
                    'status' => 'error',
                    'message' => 'Ta\'minotchi topilmadi',
                    'status_code' => 404,
                ];
            }

            $supplier = $this->supplierRepository->update($supplier, $data);

            if (! $supplier) {
                return [
                    'status' => 'error',
                    'message' => 'Ta\'minotchi ma\'lumotlarini yangilashda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Ta\'minotchi ma\'lumotlari muvaffaqiyatli yangilandi',
                'data' => $supplier,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Ta\'minotchi ma\'lumotlarini yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id)
    {
        try {
            $supplier = $this->supplierRepository->invertActive($id);

            return [
                'status' => 'success',
                'message' => 'Ta\'minotchi faollik holati muvaffaqiyatli o\'zgartirildi',
                'data' => $supplier,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Ta\'minotchi faolligini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }

    public function getById(int $id)
    {
        try {
            $supplier = $this->supplierRepository->getById($id);

            if (!$supplier) {
                return [
                    'status' => 'error',
                    'message' => 'Ta\'minotchi topilmadi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'Ta\'minotchi muvaffaqiyatli olindi',
                'data' => $supplier,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Ta\'minotchini olishda xatolik yuz berdi',
            ];
        }
    }

    public function delete(int $id)
    {
        try {
            $supplier = $this->supplierRepository->getById($id);

            if (!$supplier) {
                return [
                    'status' => 'error',
                    'message' => 'Ta\'minotchi topilmadi',
                    'status_code' => 404,
                ];
            }

            $this->supplierRepository->delete($supplier);

            return [
                'status' => 'success',
                'message' => 'Ta\'minotchi muvaffaqiyatli o\'chirildi',
                'data' => null,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Ta\'minotchini o\'chirishda xatolik yuz berdi',
            ];
        }
    }
}
