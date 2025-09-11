<?php

namespace App\Modules\Cashbox\Services;

use App\Modules\Cashbox\Interfaces\MoneyInputInterface;
use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use App\Modules\Cashbox\Enums\OtherCalculationTypesEnum;
use App\Modules\Cashbox\Interfaces\OtherCalculationInterface;
use App\Models\PaymentType;
use App\Modules\Trade\Interfaces\ClientCalculationInterface;
use App\Modules\Trade\Enums\ClientCalculationTypesEnum;
use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;
use App\Modules\Warehouse\Enums\SupplierCalculationTypesEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class MoneyInputService
{
    public function __construct(
        protected MoneyInputInterface $moneyInputRepository,
        protected ClientCalculationInterface $clientCalculationRepository,
        protected SupplierCalculationInterface $supplierCalculationRepository,
        protected OtherCalculationInterface $otherCalculationRepository
    ) {}

    public function getAllMoneyInputs(array $data = []): array
    {
        try {
            $moneyInputs = $this->moneyInputRepository->getAllMoneyInputs($data);
            return ['success' => true, 'data' => $moneyInputs];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Kirim operatsiyalarni olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function getMoneyInputById(int $id): array
    {
        try {
            $moneyInput = $this->moneyInputRepository->getMoneyInputById($id);

            if (!$moneyInput) {
                return ['success' => false, 'message' => 'Kirim operatsiya topilmadi'];
            }

            return ['success' => true, 'data' => $moneyInput];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Kirim operatsiyani olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function createMoneyInput(array $data): array
    {
        try {
            DB::beginTransaction();
            $data['user_id'] = Auth::id();

            // Date formatini o'zgartirish (dd.mm.yyyy -> Y-m-d)
            if (isset($data['date'])) {
                $data['date'] = Carbon::createFromFormat('d.m.Y', $data['date'])->format('Y-m-d');
            }

            $moneyInput = $this->moneyInputRepository->createMoneyInput($data);

            // Create calculations based on payment type
            $this->createPaymentCalculation($moneyInput, $data);

            DB::commit();

            return ['success' => true, 'data' => $moneyInput, 'message' => 'Kirim operatsiya muvaffaqiyatli yaratildi'];
        } catch (Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Kirim operatsiya yaratishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function updateMoneyInput(int $id, array $data): array
    {
        try {
            $data['user_id'] = Auth::id();
            $moneyInput = $this->moneyInputRepository->getMoneyInputById($id);

            if (!$moneyInput) {
                return ['success' => false, 'message' => 'Kirim operatsiya topilmadi'];
            }

            DB::beginTransaction();

            // Date formatini o'zgartirish (dd.mm.yyyy -> Y-m-d)
            if (isset($data['date'])) {
                $data['date'] = Carbon::createFromFormat('d.m.Y', $data['date'])->format('Y-m-d');
            }

            $updatedMoneyInput = $this->moneyInputRepository->updateMoneyInput($id, $data);

            // Update calculations based on payment type
            $this->updatePaymentCalculation($updatedMoneyInput, $data);

            DB::commit();

            return ['success' => true, 'data' => $updatedMoneyInput, 'message' => 'Kirim operatsiya muvaffaqiyatli yangilandi'];
        } catch (Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Kirim operatsiyani yangilashda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function deleteMoneyInput(int $id): array
    {
        try {
            $moneyInput = $this->moneyInputRepository->getMoneyInputById($id);

            if (!$moneyInput) {
                return ['success' => false, 'message' => 'Kirim operatsiya topilmadi'];
            }

            // Check payment_type residue before deletion
            $paymentType = PaymentType::find($moneyInput->payment_type_id);
            if (!$paymentType) {
                return ['success' => false, 'message' => 'To\'lov turi topilmadi'];
            }

            if ($paymentType->residue < $moneyInput->amount) {
                return [
                    'success' => false,
                    'message' => "Kirim operatsiyani o'chirib bo'lmaydi. {$paymentType->name} hisobida yetarli mablag' yo'q. Mavjud: " . number_format($paymentType->residue, 2) . " so'm, o'chirilishi kerak: " . number_format($moneyInput->amount, 2) . " so'm"
                ];
            }

            DB::beginTransaction();

            // Calculations will be deleted automatically via cascade delete
            $deleted = $this->moneyInputRepository->deleteMoneyInput($id);

            if ($deleted) {
                DB::commit();
                return ['success' => true, 'message' => 'Kirim operatsiya muvaffaqiyatli o\'chirildi'];
            }

            DB::rollBack();
            return ['success' => false, 'message' => 'Kirim operatsiyani o\'chirishda xatolik yuz berdi'];
        } catch (Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Kirim operatsiyani o\'chirishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    private function createPaymentCalculation($moneyInput, $data)
    {
        if ($moneyInput->type === PaymentTypesEnum::SUPPLIER_PAYMET_INPUTS->value && !empty($data['supplier_id'])) {
            $calculationValue = -$moneyInput->amount; // Negative value for supplier payment

            $this->supplierCalculationRepository->create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => $moneyInput->user_id,
                'payment_id' => $moneyInput->id,
                'type' => SupplierCalculationTypesEnum::SUPPLIER_PAYMENT->value,
                'value' => $calculationValue,
                'date' => $moneyInput->date ?? now()->format('Y-m-d'),
            ]);
        } elseif ($moneyInput->type === PaymentTypesEnum::CLIENT_PAYMET_INPUTS->value && !empty($data['client_id'])) {
            $calculationValue = -$moneyInput->amount; // Negative value for client payment

            $this->clientCalculationRepository->create([
                'client_id' => $data['client_id'],
                'user_id' => $moneyInput->user_id,
                'payment_id' => $moneyInput->id,
                'type' => ClientCalculationTypesEnum::CLIENT_PAYMENT->value,
                'value' => $calculationValue,
                'date' => $moneyInput->date ?? now()->format('Y-m-d'),
            ]);
        } elseif ($moneyInput->type === PaymentTypesEnum::OTHER_PAYMET_INPUTS->value) {
            $calculationValue = -$moneyInput->amount; // Negative value for other payment

            $this->otherCalculationRepository->create([
                'user_id' => $moneyInput->user_id,
                'payment_id' => $moneyInput->id,
                'type' => OtherCalculationTypesEnum::OTHER_PAYMENT->value,
                'value' => $calculationValue,
                'date' => $moneyInput->date ?? now()->format('Y-m-d'),
            ]);
        }
    }

    private function updatePaymentCalculation($moneyInput, $data)
    {
        // First, check for existing calculations of all types
        $existingSupplierCalculation = $this->supplierCalculationRepository->getByPaymentId($moneyInput->id);
        $existingClientCalculation = $this->clientCalculationRepository->getByPaymentId($moneyInput->id);
        $existingOtherCalculation = $this->otherCalculationRepository->getByPaymentId($moneyInput->id);

        // Handle SUPPLIER_PAYMET_INPUTS
        if ($moneyInput->type === PaymentTypesEnum::SUPPLIER_PAYMET_INPUTS->value && !empty($moneyInput->supplier_id)) {
            $calculationValue = -$moneyInput->amount; // Negative value for supplier payment

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
                    'supplier_id' => $moneyInput->supplier_id,
                    'user_id' => $moneyInput->user_id,
                    'type' => SupplierCalculationTypesEnum::SUPPLIER_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $moneyInput->date ?? now()->format('Y-m-d'),
                ]);
            } else {
                $this->supplierCalculationRepository->create([
                    'supplier_id' => $moneyInput->supplier_id,
                    'user_id' => $moneyInput->user_id,
                    'payment_id' => $moneyInput->id,
                    'type' => SupplierCalculationTypesEnum::SUPPLIER_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $moneyInput->date ?? now()->format('Y-m-d'),
                ]);
            }
        }
        // Handle CLIENT_PAYMET_INPUTS
        elseif ($moneyInput->type === PaymentTypesEnum::CLIENT_PAYMET_INPUTS->value && !empty($moneyInput->client_id)) {
            $calculationValue = -$moneyInput->amount; // Negative value for client payment

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
                    'client_id' => $moneyInput->client_id,
                    'user_id' => $moneyInput->user_id,
                    'type' => ClientCalculationTypesEnum::CLIENT_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $moneyInput->date ?? now()->format('Y-m-d'),
                ]);
            } else {
                $this->clientCalculationRepository->create([
                    'client_id' => $moneyInput->client_id,
                    'user_id' => $moneyInput->user_id,
                    'payment_id' => $moneyInput->id,
                    'type' => ClientCalculationTypesEnum::CLIENT_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $moneyInput->date ?? now()->format('Y-m-d'),
                ]);
            }
        }
        // Handle OTHER_PAYMET_INPUTS
        elseif ($moneyInput->type === PaymentTypesEnum::OTHER_PAYMET_INPUTS->value) {
            $calculationValue = -$moneyInput->amount; // Negative value for other payment

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
                    'user_id' => $moneyInput->user_id,
                    'type' => OtherCalculationTypesEnum::OTHER_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $moneyInput->date ?? now()->format('Y-m-d'),
                ]);
            } else {
                $this->otherCalculationRepository->create([
                    'user_id' => $moneyInput->user_id,
                    'payment_id' => $moneyInput->id,
                    'type' => OtherCalculationTypesEnum::OTHER_PAYMENT->value,
                    'value' => $calculationValue,
                    'date' => $moneyInput->date ?? now()->format('Y-m-d'),
                ]);
            }
        }
    }
}
