<?php

namespace App\Modules\Information\Services;

use App\Modules\Information\Interfaces\SupplierInterface;

class SupplierService
{
    public function __construct(protected SupplierInterface $supplierRepository) {}

    public function getAll(array $data)
    {
        return $this->supplierRepository->getAll($data);
    }

    public function getAllActive()
    {
        return $this->supplierRepository->getAllActive();
    }

    public function getAllWithDebt()
    {
        return $this->supplierRepository->getAllWithDebt();
    }

    public function store(array $data)
    {
        $supplier = $this->supplierRepository->store($data);

        if (!$supplier) {
            return [
                'status' => 'error',
                'message' => 'Ta\'minotchi qo\'shishda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Ta\'minotchi muvaffaqiyatli qo\'shildi',
            'data' => $supplier
        ];
    }

    public function update(int $id, array $data)
    {
        $supplier = $this->supplierRepository->getById($id);

        if (!$supplier) {
            return [
                'status' => 'error',
                'message' => 'Ta\'minotchi topilmadi',
                'status_code' => 404
            ];
        }

        $supplier = $this->supplierRepository->update($supplier, $data);

        if (!$supplier) {
            return [
                'status' => 'error',
                'message' => 'Ta\'minotchi ma\'lumotlarini yangilashda xatolik yuz berdi'
            ];
        }
        return [
            'status' => 'success',
            'message' =>  'Ta\'minotchi ma\'lumotlari muvaffaqiyatli yangilandi',
            'data' => $supplier
        ];
    }



    public function invertActive(int $id)
    {
        $supplier = $this->supplierRepository->invertActive($id);

        return [
            'status' => 'success',
            'message' => 'Ta\'minotchi faollik holati muvaffaqiyatli o\'zgartirildi',
            'data' => $supplier
        ];
    }
}
