<?php

namespace App\Modules\Cashbox\Services;

use App\Helpers\TelegramBot;
use App\Models\OtherCalculation;
use App\Models\PaymentType;
use App\Modules\Cashbox\Enums\CostTypesEnum;
use App\Modules\Cashbox\Enums\OtherCalculationTypesEnum;
use App\Modules\Cashbox\Interfaces\MoneyOutputInterface;
use Exception;
use Illuminate\Support\Facades\Auth;

class MoneyOutputService
{
    public function __construct(
        protected MoneyOutputInterface $moneyOutputRepository,
    ) {}

    public function getAllMoneyOutputs(array $data = []): array
    {
        try {
            $moneyOutputs = $this->moneyOutputRepository->getAllMoneyOutputs($data);

            return ['success' => true, 'data' => $moneyOutputs];
        } catch (Exception $e) {
            TelegramBot::sendError(request(), $e);

            return ['success' => false, 'message' => 'Chiqim operatsiyalarni olishda xatolik yuz berdi'];
        }
    }

    public function getMoneyOutputById(int $id): array
    {
        try {
            $moneyOutput = $this->moneyOutputRepository->getMoneyOutputById($id);

            if (! $moneyOutput) {
                return ['success' => false, 'message' => 'Chiqim operatsiya topilmadi'];
            }

            return ['success' => true, 'data' => $moneyOutput];
        } catch (Exception $e) {
            TelegramBot::sendError(request(), $e);

            return ['success' => false, 'message' => 'Chiqim operatsiyani olishda xatolik yuz berdi'];
        }
    }

    public function createMoneyOutput(array $data): array
    {
        try {
            $data['user_id'] = Auth::id();
            $paymentType = PaymentType::find($data['payment_type_id']);
            if (! $paymentType) {
                return ['success' => false, 'message' => 'To\'lov turi topilmadi'];
            }

            if ($paymentType->residue < $data['amount']) {
                return [
                    'success' => false,
                    'message' => "Chiqim operatsiya yaratib bo'lmaydi. {$paymentType->name} hisobida yetarli mablag' yo'q. Mavjud: ".number_format($paymentType->residue, 2)." so'm",
                ];
            }

            $moneyOutput = $this->moneyOutputRepository->createMoneyOutput($data);

            if ($data['type'] === CostTypesEnum::OTHER_PAYMENT_OUTPUT->value) {
                OtherCalculation::create([
                    'user_id' => $data['user_id'],
                    'payment_id' => null,
                    'cost_id' => $moneyOutput->id,
                    'type' => OtherCalculationTypesEnum::OTHER_COST->value,
                    'value' => -$data['amount'],
                    'date' => now()->toDateString(),
                ]);
            }

            return ['success' => true, 'data' => $moneyOutput, 'message' => 'Chiqim operatsiya muvaffaqiyatli yaratildi'];
        } catch (Exception $e) {
            TelegramBot::sendError(request(), $e);

            return ['success' => false, 'message' => 'Chiqim operatsiya yaratishda xatolik yuz berdi'];
        }
    }

    public function updateMoneyOutput(int $id, array $data): array
    {
        try {
            $moneyOutput = $this->moneyOutputRepository->getMoneyOutputById($id);
            $data['user_id'] = Auth::id();

            if (! $moneyOutput) {
                return ['success' => false, 'message' => 'Chiqim operatsiya topilmadi'];
            }

            $paymentType = PaymentType::find($data['payment_type_id']);
            if (! $paymentType) {
                return ['success' => false, 'message' => 'To\'lov turi topilmadi'];
            }

            $oldPaymentType = PaymentType::find($moneyOutput->payment_type_id);

            if (isset($data['amount']) && $data['amount'] != $moneyOutput->amount) {
                $amountDifference = $data['amount'] - $moneyOutput->amount;

                if ($amountDifference > 0) {
                    if ($moneyOutput->payment_type_id != $data['payment_type_id'] && $oldPaymentType) {
                        if ($paymentType->residue < $amountDifference) {
                            return [
                                'success' => false,
                                'message' => "Chiqim operatsiyani yangilab bo'lmaydi. {$paymentType->name} hisobida yetarli mablag' yo'q. Mavjud: ".number_format($paymentType->residue, 2)." so'm, kerak: ".number_format($amountDifference, 2)." so'm",
                            ];
                        }
                    } else {
                        $residueAfterRevert = $paymentType->residue + $moneyOutput->amount;
                        if ($residueAfterRevert < $data['amount']) {
                            return [
                                'success' => false,
                                'message' => "Chiqim operatsiyani yangilab bo'lmaydi. {$paymentType->name} hisobida yetarli mablag' yo'q. Mavjud: ".number_format($residueAfterRevert, 2)." so'm, kerak: ".number_format($data['amount'], 2)." so'm",
                            ];
                        }
                    }
                }
            } else {
                if ($moneyOutput->payment_type_id != $data['payment_type_id'] && $oldPaymentType) {
                    if ($paymentType->residue < $data['amount']) {
                        return [
                            'success' => false,
                            'message' => "Chiqim operatsiyani yangilab bo'lmaydi. {$paymentType->name} hisobida yetarli mablag' yo'q. Mavjud: ".number_format($paymentType->residue, 2)." so'm, kerak: ".number_format($data['amount'], 2)." so'm",
                        ];
                    }
                }
            }

            $updatedMoneyOutput = $this->moneyOutputRepository->updateMoneyOutput($id, $data);

            $existingOtherCalculation = OtherCalculation::where('cost_id', $moneyOutput->id)
                ->where('type', OtherCalculationTypesEnum::OTHER_COST->value)
                ->first();

            if ($data['type'] === CostTypesEnum::OTHER_PAYMENT_OUTPUT->value) {
                $calculationValue = -$data['amount'];

                if ($existingOtherCalculation) {
                    $existingOtherCalculation->update([
                        'user_id' => $data['user_id'],
                        'type' => OtherCalculationTypesEnum::OTHER_COST->value,
                        'value' => $calculationValue,
                        'date' => now()->toDateString(),
                    ]);
                } else {
                    OtherCalculation::create([
                        'user_id' => $data['user_id'],
                        'payment_id' => null,
                        'cost_id' => $updatedMoneyOutput->id,
                        'type' => OtherCalculationTypesEnum::OTHER_COST->value,
                        'value' => $calculationValue,
                        'date' => now()->toDateString(),
                    ]);
                }
            } else {
                if ($moneyOutput->type === CostTypesEnum::OTHER_PAYMENT_OUTPUT->value && $existingOtherCalculation) {
                    $existingOtherCalculation->delete();
                }
            }

            return ['success' => true, 'data' => $updatedMoneyOutput, 'message' => 'Chiqim operatsiya muvaffaqiyatli yangilandi'];
        } catch (Exception $e) {
            TelegramBot::sendError(request(), $e);

            return ['success' => false, 'message' => 'Chiqim operatsiyani yangilashda xatolik yuz berdi'];
        }
    }

    public function deleteMoneyOutput(int $id): array
    {
        try {
            $moneyOutput = $this->moneyOutputRepository->getMoneyOutputById($id);

            if (! $moneyOutput) {
                return ['success' => false, 'message' => 'Chiqim operatsiya topilmadi'];
            }

            $deleted = $this->moneyOutputRepository->deleteMoneyOutput($id);

            if ($deleted) {
                return ['success' => true, 'message' => 'Chiqim operatsiya muvaffaqiyatli o\'chirildi'];
            }

            return ['success' => false, 'message' => 'Chiqim operatsiyani o\'chirishda xatolik yuz berdi'];
        } catch (Exception $e) {
            TelegramBot::sendError(request(), $e);

            return ['success' => false, 'message' => 'Chiqim operatsiyani o\'chirishda xatolik yuz berdi'];
        }
    }
}
