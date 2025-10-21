<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Modules\Information\Interfaces\PaymentTypeInterface;

class PaymentTypeService
{
    public function __construct(
        protected PaymentTypeInterface $paymentTypeRepository,
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    public function index(array $data)
    {
        try {
            return $this->paymentTypeRepository->index($data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'To\'lov turlarini olishda xatolik yuz berdi',
            ];
        }
    }

    public function getAllActive()
    {
        try {
            return $this->paymentTypeRepository->getAllActive();
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Faol to\'lov turlarini olishda xatolik yuz berdi',
            ];
        }
    }

    public function store(array $data)
    {
        try {
            $paymentType = $this->paymentTypeRepository->store($data);

            if (! $paymentType) {
                return [
                    'status' => 'error',
                    'message' => 'To\'lov turini qo\'shishda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'To\'lov turi muvaffaqiyatli qo\'shildi',
                'data' => $paymentType,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'To\'lov turini qo\'shishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $paymentType = $this->paymentTypeRepository->getById($id);
            if (! $paymentType) {
                return [
                    'status' => 'error',
                    'message' => 'To\'lov turi topilmadi',
                    'status_code' => 404,
                ];
            }
            $updatedPaymentType = $this->paymentTypeRepository->update($paymentType, $data);
            if (! $updatedPaymentType) {
                return [
                    'status' => 'error',
                    'message' => 'To\'lov turi ma\'lumotlarini yangilashda xatolik yuz berdi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'To\'lov turi ma\'lumotlari muvaffaqiyatli yangilandi',
                'data' => $updatedPaymentType,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'To\'lov turi ma\'lumotlarini yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id)
    {
        try {
            $paymentType = $this->paymentTypeRepository->invertActive($id);

            return [
                'status' => 'success',
                'message' => 'To\'lov turi faolligi muvaffaqiyatli o\'zgartirildi',
                'data' => $paymentType,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'To\'lov turi faolligini o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }

    public function getById(int $id)
    {
        try {
            $paymentType = $this->paymentTypeRepository->getById($id);

            if (! $paymentType) {
                return [
                    'status' => 'error',
                    'message' => 'To\'lov turi topilmadi',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'To\'lov turi muvaffaqiyatli olindi',
                'data' => $paymentType,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'To\'lov turini olishda xatolik yuz berdi',
            ];
        }
    }

    public function delete(int $id)
    {
        try {
            $paymentType = $this->paymentTypeRepository->getById($id);

            if (! $paymentType) {
                return [
                    'status' => 'error',
                    'message' => 'To\'lov turi topilmadi',
                    'status_code' => 404,
                ];
            }

            $this->paymentTypeRepository->delete($paymentType);

            return [
                'status' => 'success',
                'message' => 'To\'lov turi muvaffaqiyatli o\'chirildi',
                'data' => null,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'To\'lov turini o\'chirishda xatolik yuz berdi',
            ];
        }
    }
}
