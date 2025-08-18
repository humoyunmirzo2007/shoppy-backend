<?php

namespace App\Modules\Information\Services;


use App\Modules\Information\Interfaces\CostTypeInterface;

class CostTypeService
{
    public function __construct(protected CostTypeInterface $costTypeRepository) {}


    public function getAll(array $data)
    {
        return $this->costTypeRepository->getAll($data);
    }

    public function getAllActive()
    {
        return $this->costTypeRepository->getAllActive();
    }

    public function store(array $data)
    {
        $costType = $this->costTypeRepository->store($data);

        if (!$costType) {
            return [
                'status' => 'error',
                'message' => 'Xarajat turi qo\'shishda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Xarajat turi muvaffaqiyatli qo\'shildi',
            'data' => $costType
        ];
    }

    public function update(int $id, array $data)
    {
        $costType = $this->costTypeRepository->getById($id);
        if (!$costType) {
            return [
                'status' => 'error',
                'message' => 'Xarajat turi topilmadi',
                'status_code' => 404
            ];
        }
        $costType = $this->costTypeRepository->update($costType, $data);
        if (!$costType) {
            return [
                'status' => 'error',
                'message' => 'Xarajat turi ma\'lumotlarini yangilashda xatolik yuz berdi'
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Xarajat turi ma\'lumotlari muvaffaqiyatli yangilandi',
            'data' => $costType
        ];
    }

    public function invertActive(int $id)
    {
        $costType = $this->costTypeRepository->invertActive($id);

        return [
            'status' => 'success',
            'message' => 'Xarajat turi faolligi muvaffaqiyatli o\'zgartirildi',
            'data' => $costType
        ];
    }
}
