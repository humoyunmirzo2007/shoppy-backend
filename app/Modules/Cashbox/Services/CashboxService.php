<?php

namespace App\Modules\Cashbox\Services;

use App\Modules\Cashbox\Interfaces\CashboxInterface;
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
