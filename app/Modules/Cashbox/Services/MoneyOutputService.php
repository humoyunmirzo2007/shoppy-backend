<?php

namespace App\Modules\Cashbox\Services;

use App\Modules\Cashbox\Interfaces\MoneyOutputInterface;
use App\Modules\Cashbox\Enums\CostTypesEnum;
use App\Modules\Cashbox\Enums\OtherCalculationTypesEnum;
use App\Models\OtherCalculation;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentType;
use Exception;

class MoneyOutputService
{
    public function __construct(protected MoneyOutputInterface $moneyOutputRepository) {}

    public function getAllMoneyOutputs(array $data = []): array
    {
        try {
            $moneyOutputs = $this->moneyOutputRepository->getAllMoneyOutputs($data);
            return ['success' => true, 'data' => $moneyOutputs];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Chiqim operatsiyalarni olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function getMoneyOutputById(int $id): array
    {
        try {
            $moneyOutput = $this->moneyOutputRepository->getMoneyOutputById($id);

            if (!$moneyOutput) {
                return ['success' => false, 'message' => 'Chiqim operatsiya topilmadi'];
            }

            return ['success' => true, 'data' => $moneyOutput];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Chiqim operatsiyani olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function createMoneyOutput(array $data): array
    {
        try {
            $data['user_id'] = Auth::id();
            // Check payment_type residue before creating cost
            $paymentType = PaymentType::find($data['payment_type_id']);
            if (!$paymentType) {
                return ['success' => false, 'message' => 'To\'lov turi topilmadi'];
            }

            if ($paymentType->residue < $data['amount']) {
                return [
                    'success' => false,
                    'message' => "Chiqim operatsiya yaratib bo'lmaydi. {$paymentType->name} hisobida yetarli mablag' yo'q. Mavjud: " . number_format($paymentType->residue, 2) . " so'm"
                ];
            }

            $moneyOutput = $this->moneyOutputRepository->createMoneyOutput($data);

            // If OTHER_PAYMET_OUTPUT, create other_calculation record
            if ($data['type'] === CostTypesEnum::OTHER_PAYMET_OUTPUT->value) {
                OtherCalculation::create([
                    'user_id' => $data['user_id'],
                    'payment_id' => null, // OTHER_COST is not linked to payment
                    'cost_id' => $moneyOutput->id, // Link to the money operation
                    'type' => OtherCalculationTypesEnum::OTHER_COST->value,
                    'value' => -$data['amount'], // Negative because it's a cost
                    'date' => now()->toDateString()
                ]);
            }

            return ['success' => true, 'data' => $moneyOutput, 'message' => 'Chiqim operatsiya muvaffaqiyatli yaratildi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Chiqim operatsiya yaratishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function updateMoneyOutput(int $id, array $data): array
    {
        try {
            $moneyOutput = $this->moneyOutputRepository->getMoneyOutputById($id);
            $data['user_id'] = Auth::id();

            if (!$moneyOutput) {
                return ['success' => false, 'message' => 'Chiqim operatsiya topilmadi'];
            }

            // Check payment_type residue before updating cost
            $paymentType = PaymentType::find($data['payment_type_id']);
            if (!$paymentType) {
                return ['success' => false, 'message' => 'To\'lov turi topilmadi'];
            }

            // Calculate the difference: if we're changing payment_type or amount
            $oldPaymentType = PaymentType::find($moneyOutput->payment_type_id);
            $residueAfterRevert = $paymentType->residue;

            // If changing payment_type, we need to consider the old amount will be reverted
            if ($moneyOutput->payment_type_id != $data['payment_type_id'] && $oldPaymentType) {
                // Different payment types, so new payment_type will get full impact
                $residueAfterRevert = $paymentType->residue;
            } else {
                // Same payment type, so we revert old amount and apply new amount
                $residueAfterRevert = $paymentType->residue + $moneyOutput->amount;
            }

            if ($residueAfterRevert < $data['amount']) {
                return [
                    'success' => false,
                    'message' => "Chiqim operatsiya yangilab bo'lmaydi. {$paymentType->name} hisobida yetarli mablag' yo'q. Mavjud: " . number_format($residueAfterRevert, 2) . " so'm"
                ];
            }

            $updatedMoneyOutput = $this->moneyOutputRepository->updateMoneyOutput($id, $data);

            // Handle other_calculation for OTHER_COST
            $existingOtherCalculation = OtherCalculation::where('cost_id', $moneyOutput->id)
                ->where('type', OtherCalculationTypesEnum::OTHER_COST->value)
                ->first();

            // If new cost is OTHER_PAYMET_OUTPUT
            if ($data['type'] === CostTypesEnum::OTHER_PAYMET_OUTPUT->value) {
                $calculationValue = -$data['amount']; // Negative because it's a cost

                // Update existing other_calculation or create new one
                if ($existingOtherCalculation) {
                    $existingOtherCalculation->update([
                        'user_id' => $data['user_id'],
                        'type' => OtherCalculationTypesEnum::OTHER_COST->value,
                        'value' => $calculationValue,
                        'date' => now()->toDateString()
                    ]);
                } else {
                    OtherCalculation::create([
                        'user_id' => $data['user_id'],
                        'payment_id' => null,
                        'cost_id' => $updatedMoneyOutput->id,
                        'type' => OtherCalculationTypesEnum::OTHER_COST->value,
                        'value' => $calculationValue,
                        'date' => now()->toDateString()
                    ]);
                }
            } else {
                // If old cost was OTHER_PAYMET_OUTPUT but new cost is not, delete the other_calculation
                if ($moneyOutput->type === CostTypesEnum::OTHER_PAYMET_OUTPUT->value && $existingOtherCalculation) {
                    $existingOtherCalculation->delete();
                }
            }

            return ['success' => true, 'data' => $updatedMoneyOutput, 'message' => 'Chiqim operatsiya muvaffaqiyatli yangilandi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Chiqim operatsiyani yangilashda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function deleteMoneyOutput(int $id): array
    {
        try {
            $moneyOutput = $this->moneyOutputRepository->getMoneyOutputById($id);

            if (!$moneyOutput) {
                return ['success' => false, 'message' => 'Chiqim operatsiya topilmadi'];
            }

            // If it's OTHER_COST, the related other_calculation will be deleted automatically via cascade delete
            // No need to manually delete it since we have cascadeOnDelete() in the migration

            $deleted = $this->moneyOutputRepository->deleteMoneyOutput($id);

            if ($deleted) {
                return ['success' => true, 'message' => 'Chiqim operatsiya muvaffaqiyatli o\'chirildi'];
            }

            return ['success' => false, 'message' => 'Chiqim operatsiyani o\'chirishda xatolik yuz berdi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Chiqim operatsiyani o\'chirishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }
}
