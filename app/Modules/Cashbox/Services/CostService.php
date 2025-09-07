<?php

namespace App\Modules\Cashbox\Services;

use App\Modules\Cashbox\Interfaces\CostInterface;
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
            $cost = $this->costRepository->store($data);
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

            $updatedCost = $this->costRepository->update($id, $data);
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
