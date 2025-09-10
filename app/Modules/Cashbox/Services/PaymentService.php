<?php

namespace App\Modules\Cashbox\Services;

use App\Modules\Cashbox\Interfaces\PaymentInterface;
use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use App\Modules\Cashbox\Enums\OtherCalculationTypesEnum;
use App\Modules\Cashbox\Interfaces\OtherCalculationInterface;
use App\Modules\Trade\Interfaces\ClientCalculationInterface;
use App\Modules\Trade\Enums\ClientCalculationTypesEnum;
use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;
use App\Modules\Warehouse\Enums\SupplierCalculationTypesEnum;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        protected PaymentInterface $paymentRepository,
        protected ClientCalculationInterface $clientCalculationRepository,
        protected SupplierCalculationInterface $supplierCalculationRepository,
        protected OtherCalculationInterface $otherCalculationRepository
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

            // Calculations will be deleted automatically via cascade delete
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
        if ($payment->type === PaymentTypesEnum::SUPPLIER_PAYMET_INPUTS->value && !empty($data['supplier_id'])) {
            $calculationValue = -$payment->amount; // Negative value for supplier payment

            $this->supplierCalculationRepository->create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
                'type' => SupplierCalculationTypesEnum::SUPPLIER_PAYMENT->value,
                'value' => $calculationValue,
                'date' => $payment->date ?? now()->format('Y-m-d'),
            ]);
        } elseif ($payment->type === PaymentTypesEnum::CLIENT_PAYMET_INPUTS->value && !empty($data['client_id'])) {
            $calculationValue = -$payment->amount; // Negative value for client payment

            $this->clientCalculationRepository->create([
                'client_id' => $data['client_id'],
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
                'type' => ClientCalculationTypesEnum::CLIENT_PAYMENT->value,
                'value' => $calculationValue,
                'date' => $payment->date ?? now()->format('Y-m-d'),
            ]);
        } elseif ($payment->type === PaymentTypesEnum::OTHER_PAYMET_INPUTS->value) {
            $calculationValue = -$payment->amount; // Negative value for other payment

            $this->otherCalculationRepository->create([
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
                'type' => OtherCalculationTypesEnum::OTHER_PAYMENT->value,
                'value' => $calculationValue,
                'date' => $payment->date ?? now()->format('Y-m-d'),
            ]);
        }
    }

    private function updatePaymentCalculation($payment, $data)
    {
        // First, check for existing calculations of all types
        $existingSupplierCalculation = $this->supplierCalculationRepository->getByPaymentId($payment->id);
        $existingClientCalculation = $this->clientCalculationRepository->getByPaymentId($payment->id);
        $existingOtherCalculation = $this->otherCalculationRepository->getByPaymentId($payment->id);

        // Handle SUPPLIER_PAYMET_INPUTS
        if ($payment->type === PaymentTypesEnum::SUPPLIER_PAYMET_INPUTS->value && !empty($payment->supplier_id)) {
            $calculationValue = -$payment->amount; // Negative value for supplier payment

            // Delete any existing client or other calculations (if payment type changed)
            if ($existingClientCalculation) {
                $this->clientCalculationRepository->delete($existingClientCalculation->id);
            }
            if ($existingOtherCalculation) {
                $this->otherCalculationRepository->delete($existingOtherCalculation->id);
            }

            // Update or create supplier calculation
            if ($existingSupplierCalculation) {
                $this->supplierCalculationRepository->update($existingSupplierCalculation->id, [
                    'supplier_id' => $payment->supplier_id,
                    'user_id' => $payment->user_id,
                    'type' => SupplierCalculationTypesEnum::SUPPLIER_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            } else {
                $this->supplierCalculationRepository->create([
                    'supplier_id' => $payment->supplier_id,
                    'user_id' => $payment->user_id,
                    'payment_id' => $payment->id,
                    'type' => SupplierCalculationTypesEnum::SUPPLIER_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            }
        }
        // Handle CLIENT_PAYMET_INPUTS
        elseif ($payment->type === PaymentTypesEnum::CLIENT_PAYMET_INPUTS->value && !empty($payment->client_id)) {
            $calculationValue = -$payment->amount; // Negative value for client payment

            // Delete any existing supplier or other calculations (if payment type changed)
            if ($existingSupplierCalculation) {
                $this->supplierCalculationRepository->delete($existingSupplierCalculation->id);
            }
            if ($existingOtherCalculation) {
                $this->otherCalculationRepository->delete($existingOtherCalculation->id);
            }

            // Update or create client calculation
            if ($existingClientCalculation) {
                $this->clientCalculationRepository->update($existingClientCalculation->id, [
                    'client_id' => $payment->client_id,
                    'user_id' => $payment->user_id,
                    'type' => ClientCalculationTypesEnum::CLIENT_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            } else {
                $this->clientCalculationRepository->create([
                    'client_id' => $payment->client_id,
                    'user_id' => $payment->user_id,
                    'payment_id' => $payment->id,
                    'type' => ClientCalculationTypesEnum::CLIENT_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            }
        }
        // Handle OTHER_PAYMET_INPUTS
        elseif ($payment->type === PaymentTypesEnum::OTHER_PAYMET_INPUTS->value) {
            $calculationValue = -$payment->amount; // Negative value for other payment

            // Delete any existing supplier or client calculations (if payment type changed)
            if ($existingSupplierCalculation) {
                $this->supplierCalculationRepository->delete($existingSupplierCalculation->id);
            }
            if ($existingClientCalculation) {
                $this->clientCalculationRepository->delete($existingClientCalculation->id);
            }

            // Update or create other calculation
            if ($existingOtherCalculation) {
                $this->otherCalculationRepository->update($existingOtherCalculation->id, [
                    'user_id' => $payment->user_id,
                    'type' => OtherCalculationTypesEnum::OTHER_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            } else {
                $this->otherCalculationRepository->create([
                    'user_id' => $payment->user_id,
                    'payment_id' => $payment->id,
                    'type' => OtherCalculationTypesEnum::OTHER_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $payment->date ?? now()->format('Y-m-d'),
                ]);
            }
        }
    }
}
