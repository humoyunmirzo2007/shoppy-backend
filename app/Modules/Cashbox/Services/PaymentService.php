<?php

namespace App\Modules\Cashbox\Services;

use App\Modules\Cashbox\Interfaces\PaymentInterface;
use Exception;

class PaymentService
{
    public function __construct(protected PaymentInterface $paymentRepository) {}

    public function getAllPayments(array $filters = []): array
    {
        try {
            $payments = $this->paymentRepository->getAll($filters);
            return ['success' => true, 'data' => $payments];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'To\'lovlarni olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function getPaymentById(int $id): array
    {
        try {
            $payment = $this->paymentRepository->getById($id);

            if (!$payment) {
                return ['success' => false, 'message' => 'To\'lov topilmadi'];
            }

            return ['success' => true, 'data' => $payment];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'To\'lovni olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function createPayment(array $data): array
    {
        try {
            $payment = $this->paymentRepository->store($data);
            return ['success' => true, 'data' => $payment, 'message' => 'To\'lov muvaffaqiyatli yaratildi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'To\'lov yaratishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function updatePayment(int $id, array $data): array
    {
        try {
            $payment = $this->paymentRepository->getById($id);

            if (!$payment) {
                return ['success' => false, 'message' => 'To\'lov topilmadi'];
            }

            $updatedPayment = $this->paymentRepository->update($id, $data);
            return ['success' => true, 'data' => $updatedPayment, 'message' => 'To\'lov muvaffaqiyatli yangilandi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'To\'lovni yangilashda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function deletePayment(int $id): array
    {
        try {
            $payment = $this->paymentRepository->getById($id);

            if (!$payment) {
                return ['success' => false, 'message' => 'To\'lov topilmadi'];
            }

            $deleted = $this->paymentRepository->delete($id);

            if ($deleted) {
                return ['success' => true, 'message' => 'To\'lov muvaffaqiyatli o\'chirildi'];
            }

            return ['success' => false, 'message' => 'To\'lovni o\'chirishda xatolik yuz berdi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'To\'lovni o\'chirishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }
}
