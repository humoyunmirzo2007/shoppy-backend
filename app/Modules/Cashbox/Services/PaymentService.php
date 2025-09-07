<?php

namespace App\Modules\Cashbox\Services;

use App\Modules\Cashbox\Interfaces\PaymentInterface;
use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use App\Modules\Trade\Interfaces\ClientCalculationInterface;
use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        protected PaymentInterface $paymentRepository,
        protected ClientCalculationInterface $clientCalculationRepository,
        protected SupplierCalculationInterface $supplierCalculationRepository
    ) {}

    public function getAllPayments(array $data = []): array
    {
        try {
            $payments = $this->paymentRepository->getAll($data);
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
            DB::beginTransaction();

            // Date formatini o'zgartirish (dd.mm.yyyy -> Y-m-d)
            if (isset($data['date'])) {
                $data['date'] = Carbon::createFromFormat('d.m.Y', $data['date'])->format('Y-m-d');
            }

            $payment = $this->paymentRepository->store($data);

            // Create calculations based on payment type
            $this->createPaymentCalculation($payment, $data);

            DB::commit();

            return ['success' => true, 'data' => $payment, 'message' => 'To\'lov muvaffaqiyatli yaratildi'];
        } catch (Exception $e) {
            DB::rollBack();
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

            DB::beginTransaction();

            // Date formatini o'zgartirish (dd.mm.yyyy -> Y-m-d)
            if (isset($data['date'])) {
                $data['date'] = Carbon::createFromFormat('d.m.Y', $data['date'])->format('Y-m-d');
            }

            $updatedPayment = $this->paymentRepository->update($id, $data);

            // Update calculations based on payment type
            $this->updatePaymentCalculation($updatedPayment, $data);

            DB::commit();

            return ['success' => true, 'data' => $updatedPayment, 'message' => 'To\'lov muvaffaqiyatli yangilandi'];
        } catch (Exception $e) {
            DB::rollBack();
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

            DB::beginTransaction();

            // Delete calculations before deleting payment
            $this->deletePaymentCalculation($payment);

            $deleted = $this->paymentRepository->delete($id);

            if ($deleted) {
                DB::commit();
                return ['success' => true, 'message' => 'To\'lov muvaffaqiyatli o\'chirildi'];
            }

            DB::rollBack();
            return ['success' => false, 'message' => 'To\'lovni o\'chirishda xatolik yuz berdi'];
        } catch (Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'To\'lovni o\'chirishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    private function createPaymentCalculation($payment, $data)
    {
        if ($payment->type === PaymentTypesEnum::SUPPLIER_PAYMENT && !empty($data['supplier_id'])) {
            $calculationValue = -$payment->amount; // Negative value for supplier payment

            $this->supplierCalculationRepository->create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
                'type' => 'SUPPLIER_PAYMENT',
                'value' => $calculationValue,
                'date' => $payment->date ?? now()->format('Y-m-d'),
            ]);
        } elseif ($payment->type === PaymentTypesEnum::CLIENT_PAYMENT && !empty($data['client_id'])) {
            $calculationValue = -$payment->amount; // Negative value for client payment

            $this->clientCalculationRepository->create([
                'client_id' => $data['client_id'],
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
                'type' => 'CLIENT_PAYMENT',
                'value' => $calculationValue,
                'date' => $payment->date ?? now()->format('Y-m-d'),
            ]);
        }
    }

    private function updatePaymentCalculation($payment, $data)
    {
        // First, check for existing calculations of both types
        $existingSupplierCalculation = $this->supplierCalculationRepository->getByPaymentId($payment->id);
        $existingClientCalculation = $this->clientCalculationRepository->getByPaymentId($payment->id);

        // Handle SUPPLIER_PAYMENT
        if ($payment->type === PaymentTypesEnum::SUPPLIER_PAYMENT && !empty($payment->supplier_id)) {
            $calculationValue = -$payment->amount; // Negative value for supplier payment

            // Delete any existing client calculation (if payment type changed)
            if ($existingClientCalculation) {
                $this->clientCalculationRepository->delete($existingClientCalculation->id);
            }

            // Update or create supplier calculation
            if ($existingSupplierCalculation) {
                $this->supplierCalculationRepository->update($existingSupplierCalculation->id, [
                    'supplier_id' => $payment->supplier_id,
                    'user_id' => $payment->user_id,
                    'type' => 'SUPPLIER_PAYMENT',
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            } else {
                $this->supplierCalculationRepository->create([
                    'supplier_id' => $payment->supplier_id,
                    'user_id' => $payment->user_id,
                    'payment_id' => $payment->id,
                    'type' => 'SUPPLIER_PAYMENT',
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            }
        }
        // Handle CLIENT_PAYMENT
        elseif ($payment->type === PaymentTypesEnum::CLIENT_PAYMENT && !empty($payment->client_id)) {
            $calculationValue = -$payment->amount; // Negative value for client payment

            // Delete any existing supplier calculation (if payment type changed)
            if ($existingSupplierCalculation) {
                $this->supplierCalculationRepository->delete($existingSupplierCalculation->id);
            }

            // Update or create client calculation
            if ($existingClientCalculation) {
                $this->clientCalculationRepository->update($existingClientCalculation->id, [
                    'client_id' => $payment->client_id,
                    'user_id' => $payment->user_id,
                    'type' => 'CLIENT_PAYMENT',
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            } else {
                $this->clientCalculationRepository->create([
                    'client_id' => $payment->client_id,
                    'user_id' => $payment->user_id,
                    'payment_id' => $payment->id,
                    'type' => 'CLIENT_PAYMENT',
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            }
        }
        // Handle OTHER_PAYMENT - delete all existing calculations
        elseif ($payment->type === PaymentTypesEnum::OTHER_PAYMENT) {
            if ($existingSupplierCalculation) {
                $this->supplierCalculationRepository->delete($existingSupplierCalculation->id);
            }
            if ($existingClientCalculation) {
                $this->clientCalculationRepository->delete($existingClientCalculation->id);
            }
        }
    }

    private function deletePaymentCalculation($payment)
    {
        if ($payment->type === PaymentTypesEnum::SUPPLIER_PAYMENT) {
            $existingCalculation = $this->supplierCalculationRepository->getByPaymentId($payment->id);
            if ($existingCalculation) {
                $this->supplierCalculationRepository->delete($existingCalculation->id);
            }
        } elseif ($payment->type === PaymentTypesEnum::CLIENT_PAYMENT) {
            $existingCalculation = $this->clientCalculationRepository->getByPaymentId($payment->id);
            if ($existingCalculation) {
                $this->clientCalculationRepository->delete($existingCalculation->id);
            }
        }
    }
}
