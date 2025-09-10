<?php

namespace App\Modules\Cashbox\Services;

use App\Modules\Cashbox\Interfaces\CostInterface;
use App\Modules\Cashbox\Enums\CostTypesEnum;
use App\Modules\Cashbox\Enums\OtherCalculationTypesEnum;
use App\Models\OtherCalculation;
use App\Models\PaymentType;
use Exception;

class CostService
{
    public function __construct(protected CostInterface $costRepository) {}

    public function getAllCosts(array $data = []): array
    {
        try {
            $costs = $this->costRepository->getAll($data);
            return ['success' => true, 'data' => $costs];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Xarajatlarni olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function getCostById(int $id): array
    {
        try {
            $cost = $this->costRepository->getById($id);

            if (!$cost) {
                return ['success' => false, 'message' => 'Xarajat topilmadi'];
            }

            return ['success' => true, 'data' => $cost];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Xarajatni olishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function createCost(array $data): array
    {
        try {
            // Check payment_type residue before creating cost
            $paymentType = PaymentType::find($data['payment_type_id']);
            if (!$paymentType) {
                return ['success' => false, 'message' => 'To\'lov turi topilmadi'];
            }

            if ($paymentType->residue < $data['amount']) {
                return [
                    'success' => false,
                    'message' => "Xarajat yaratib bo'lmaydi. {$paymentType->name} hisobida yetarli mablag' yo'q. Mavjud: " . number_format($paymentType->residue, 2) . " so'm"
                ];
            }

            $cost = $this->costRepository->store($data);

            // If OTHER_PAYMET_OUTPUT, create other_calculation record
            if ($data['type'] === CostTypesEnum::OTHER_PAYMET_OUTPUT->value) {
                OtherCalculation::create([
                    'user_id' => $data['user_id'],
                    'payment_id' => null, // OTHER_COST is not linked to payment
                    'cost_id' => $cost->id, // Link to the cost
                    'type' => OtherCalculationTypesEnum::OTHER_COST->value,
                    'value' => -$data['amount'], // Negative because it's a cost
                    'date' => now()->toDateString()
                ]);
            }

            return ['success' => true, 'data' => $cost, 'message' => 'Xarajat muvaffaqiyatli yaratildi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Xarajat yaratishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function updateCost(int $id, array $data): array
    {
        try {
            $cost = $this->costRepository->getById($id);

            if (!$cost) {
                return ['success' => false, 'message' => 'Xarajat topilmadi'];
            }

            // Check payment_type residue before updating cost
            $paymentType = PaymentType::find($data['payment_type_id']);
            if (!$paymentType) {
                return ['success' => false, 'message' => 'To\'lov turi topilmadi'];
            }

            // Calculate the difference: if we're changing payment_type or amount
            $oldPaymentType = PaymentType::find($cost->payment_type_id);
            $residueAfterRevert = $paymentType->residue;

            // If changing payment_type, we need to consider the old amount will be reverted
            if ($cost->payment_type_id != $data['payment_type_id'] && $oldPaymentType) {
                // Different payment types, so new payment_type will get full impact
                $residueAfterRevert = $paymentType->residue;
            } else {
                // Same payment type, so we revert old amount and apply new amount
                $residueAfterRevert = $paymentType->residue + $cost->amount;
            }

            if ($residueAfterRevert < $data['amount']) {
                return [
                    'success' => false,
                    'message' => "Xarajat yangilab bo'lmaydi. {$paymentType->name} hisobida yetarli mablag' yo'q. Mavjud: " . number_format($residueAfterRevert, 2) . " so'm"
                ];
            }

            $updatedCost = $this->costRepository->update($id, $data);

            // Handle other_calculation for OTHER_COST
            $existingOtherCalculation = OtherCalculation::where('cost_id', $cost->id)
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
                        'cost_id' => $updatedCost->id,
                        'type' => OtherCalculationTypesEnum::OTHER_COST->value,
                        'value' => $calculationValue,
                        'date' => now()->toDateString()
                    ]);
                }
            } else {
                // If old cost was OTHER_PAYMET_OUTPUT but new cost is not, delete the other_calculation
                if ($cost->type === CostTypesEnum::OTHER_PAYMET_OUTPUT->value && $existingOtherCalculation) {
                    $existingOtherCalculation->delete();
                }
            }

            return ['success' => true, 'data' => $updatedCost, 'message' => 'Xarajat muvaffaqiyatli yangilandi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Xarajatni yangilashda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }

    public function deleteCost(int $id): array
    {
        try {
            $cost = $this->costRepository->getById($id);

            if (!$cost) {
                return ['success' => false, 'message' => 'Xarajat topilmadi'];
            }

            // If it's OTHER_COST, the related other_calculation will be deleted automatically via cascade delete
            // No need to manually delete it since we have cascadeOnDelete() in the migration

            $deleted = $this->costRepository->delete($id);

            if ($deleted) {
                return ['success' => true, 'message' => 'Xarajat muvaffaqiyatli o\'chirildi'];
            }

            return ['success' => false, 'message' => 'Xarajatni o\'chirishda xatolik yuz berdi'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Xarajatni o\'chirishda xatolik yuz berdi: ' . $e->getMessage()];
        }
    }
}
