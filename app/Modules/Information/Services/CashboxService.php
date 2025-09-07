<?php

namespace App\Modules\Information\Services;

use App\Modules\Information\Interfaces\CashboxInterface;
use App\Models\Cashbox;
use Illuminate\Database\Eloquent\Collection;

class CashboxService
{
    protected CashboxInterface $cashboxRepository;

    public function __construct(CashboxInterface $cashboxRepository)
    {
        $this->cashboxRepository = $cashboxRepository;
    }

    public function getAllCashboxes(array $filters = []): array
    {
        try {
            $cashboxes = $this->cashboxRepository->getAll($filters);

            return [
                'success' => true,
                'data' => $cashboxes,
                'message' => 'Kassa ro\'yxati muvaffaqiyatli olindi'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kassa ro\'yxatini olishda xatolik yuz berdi: ' . $e->getMessage()
            ];
        }
    }

    public function getCashboxById(int $id): array
    {
        try {
            $cashbox = $this->cashboxRepository->getById($id);

            if (!$cashbox) {
                return [
                    'success' => false,
                    'message' => 'Kassa topilmadi'
                ];
            }

            return [
                'success' => true,
                'data' => $cashbox,
                'message' => 'Kassa ma\'lumotlari muvaffaqiyatli olindi'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kassa ma\'lumotlarini olishda xatolik yuz berdi: ' . $e->getMessage()
            ];
        }
    }

    public function createCashbox(array $data): array
    {
        try {
            // Check if cashbox with same name, user_id and payment_type_id already exists
            $existing = $this->cashboxRepository->getAll([
                'name' => $data['name'],
                'user_id' => $data['user_id'],
                'payment_type_id' => $data['payment_type_id']
            ]);

            if ($existing->isNotEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Bu nom, foydalanuvchi va to\'lov turi bilan kassa allaqachon mavjud'
                ];
            }

            $cashbox = $this->cashboxRepository->create($data);

            return [
                'success' => true,
                'data' => $cashbox,
                'message' => 'Kassa muvaffaqiyatli yaratildi'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kassa yaratishda xatolik yuz berdi: ' . $e->getMessage()
            ];
        }
    }

    public function updateCashbox(int $id, array $data): array
    {
        try {
            $cashbox = $this->cashboxRepository->update($id, $data);

            if (!$cashbox) {
                return [
                    'success' => false,
                    'message' => 'Kassa topilmadi'
                ];
            }

            return [
                'success' => true,
                'data' => $cashbox,
                'message' => 'Kassa muvaffaqiyatli yangilandi'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kassa yangilashda xatolik yuz berdi: ' . $e->getMessage()
            ];
        }
    }

    public function deleteCashbox(int $id): array
    {
        try {
            $deleted = $this->cashboxRepository->delete($id);

            if (!$deleted) {
                return [
                    'success' => false,
                    'message' => 'Kassa topilmadi yoki o\'chirishda xatolik yuz berdi'
                ];
            }

            return [
                'success' => true,
                'message' => 'Kassa muvaffaqiyatli o\'chirildi'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kassa o\'chirishda xatolik yuz berdi: ' . $e->getMessage()
            ];
        }
    }

    public function toggleCashboxActive(int $id): array
    {
        try {
            $cashbox = $this->cashboxRepository->toggleActive($id);

            if (!$cashbox) {
                return [
                    'success' => false,
                    'message' => 'Kassa topilmadi'
                ];
            }

            $status = $cashbox->is_active ? 'faollashtirildi' : 'nofaol qilindi';

            return [
                'success' => true,
                'data' => $cashbox,
                'message' => "Kassa muvaffaqiyatli {$status}"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kassa holatini o\'zgartirishda xatolik yuz berdi: ' . $e->getMessage()
            ];
        }
    }
}
