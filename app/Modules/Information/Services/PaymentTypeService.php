<?php

namespace App\Modules\Information\Services;

use App\Modules\Information\Interfaces\PaymentTypeInterface;

class PaymentTypeService
{
    public function __construct(protected PaymentTypeInterface $paymentTypeRepository) {}

    public function index(array $data)
    {
        return $this->paymentTypeRepository->index($data);
    }

    public function getAllActive()
    {
        return $this->paymentTypeRepository->getAllActive();
    }

    public function store(array $data)
    {
        $paymentType = $this->paymentTypeRepository->store($data);

        if (!$paymentType) {
            return [
                'status' => 'error',
                'message' => 'To\'lov turini qo\'shishda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'To\'lov turi muvaffaqiyatli qo\'shildi',
            'data' => $paymentType
        ];
    }

    public function update(int $id, array $data)
    {
        $paymentType = $this->paymentTypeRepository->getById($id);
        if (!$paymentType) {
            return [
                'status' => 'error',
                'message' => 'To\'lov turi topilmadi',
                'status_code' => 404
            ];
        }
        $updatedPaymentType = $this->paymentTypeRepository->update($paymentType, $data);
        if (!$updatedPaymentType) {
            return [
                'status' => 'error',
                'message' => 'To\'lov turi ma\'lumotlarini yangilashda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'To\'lov turi ma\'lumotlari muvaffaqiyatli yangilandi',
            'data' => $updatedPaymentType
        ];
    }

    public function invertActive(int $id)
    {
        $paymentType = $this->paymentTypeRepository->invertActive($id);

        return [
            'status' => 'success',
            'message' => 'To\'lov turi faolligi muvaffaqiyatli o\'zgartirildi',
            'data' => $paymentType
        ];
    }
}
