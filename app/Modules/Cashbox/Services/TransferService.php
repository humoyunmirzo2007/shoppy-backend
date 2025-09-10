<?php

namespace App\Modules\Cashbox\Services;

use App\Modules\Cashbox\Interfaces\PaymentInterface;
use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use App\Modules\Information\Interfaces\PaymentTypeInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class TransferService
{
    public function __construct(
        protected PaymentInterface $paymentRepository,
        protected PaymentTypeInterface $paymentTypeRepository
    ) {}

    public function getTransfers(array $data): array
    {
        try {
            $transfers = $this->paymentRepository->getTransfers($data);

            return ['success' => true, 'data' => $transfers, 'message' => 'O\'tkazmalar ro\'yxati olindi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'O\'tkazmalarni olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function getTransferById(int $id): array
    {
        try {
            $transfer = $this->paymentRepository->getTransferById($id);

            if (!$transfer) {
                return ['success' => false, 'message' => 'O\'tkazma topilmadi'];
            }

            return ['success' => true, 'data' => $transfer, 'message' => 'O\'tkazma ma\'lumotlari olindi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'O\'tkazmani olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function createTransfer(array $data): array
    {
        try {
            DB::beginTransaction();

            // Validate that both payment types exist and are different
            if ($data['payment_type_id'] === $data['other_payment_type_id']) {
                return ['success' => false, 'message' => 'Bir xil to\'lov turidan o\'tkazma qilib bo\'lmaydi'];
            }

            $fromPaymentType = $this->paymentTypeRepository->getById($data['payment_type_id']);
            $toPaymentType = $this->paymentTypeRepository->getById($data['other_payment_type_id']);

            if (!$fromPaymentType || !$toPaymentType) {
                return ['success' => false, 'message' => 'To\'lov turlari topilmadi'];
            }

            // Check if source payment type has enough balance
            if ($fromPaymentType->residue < $data['amount']) {
                return ['success' => false, 'message' => 'Yetarli qoldiq mavjud emas. Mavjud qoldiq: ' . $fromPaymentType->residue];
            }

            // Create transfer payment record
            $transferData = [
                'user_id' => $data['user_id'],
                'payment_type_id' => $data['payment_type_id'],
                'other_payment_type_id' => $data['other_payment_type_id'],
                'amount' => $data['amount'],
                'description' => $data['description'] ?? null,
                'type' => PaymentTypesEnum::TRANSFER->value,
                'date' => $data['date'] ?? now()->format('Y-m-d'),
            ];

            $transfer = $this->paymentRepository->store($transferData);

            // Load relationships for response
            $transfer->load(['user:id,full_name', 'paymentType:id,name', 'otherPaymentType:id,name']);

            DB::commit();

            return ['success' => true, 'data' => $transfer, 'message' => 'O\'tkazma muvaffaqiyatli yaratildi'];
        } catch (Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'O\'tkazmani yaratishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function deleteTransfer(int $id): array
    {
        try {
            $transfer = $this->paymentRepository->getTransferById($id);

            if (!$transfer) {
                return ['success' => false, 'message' => 'O\'tkazma topilmadi'];
            }

            DB::beginTransaction();

            // Check if destination payment type has enough balance to reverse the transfer
            $destinationPaymentType = $this->paymentTypeRepository->getById($transfer->other_payment_type_id);

            if (!$destinationPaymentType) {
                return ['success' => false, 'message' => 'O\'tkazilgan to\'lov turi topilmadi'];
            }

            if ($destinationPaymentType->residue < $transfer->amount) {
                return ['success' => false, 'message' => 'O\'tkazilgan to\'lov turida yetarli qoldiq mavjud emas. Mavjud qoldiq: ' . $destinationPaymentType->residue];
            }

            $this->paymentRepository->delete($id);

            DB::commit();

            return ['success' => true, 'message' => 'O\'tkazma muvaffaqiyatli o\'chirildi'];
        } catch (Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'O\'tkazmani o\'chirishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }
}
