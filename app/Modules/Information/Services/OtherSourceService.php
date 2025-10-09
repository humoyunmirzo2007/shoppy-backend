<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBugNotifier;
use App\Models\OtherSource;
use App\Modules\Information\Interfaces\OtherSourceInterface;
use Illuminate\Database\Eloquent\Collection;

class OtherSourceService
{
    public function __construct(
        private OtherSourceInterface $otherSourceRepository,
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    public function getAll(array $data)
    {
        try {
            return $this->otherSourceRepository->getAll($data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'success' => false,
                'message' => 'Manbalarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function getByTypeAllActive(array $data): Collection|array
    {
        try {
            $type = $data['type'];

            return $this->otherSourceRepository->getByTypeAllActive($type);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'success' => false,
                'message' => 'Faol manbalarni olishda xatolik yuz berdi',
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $otherSource = $this->otherSourceRepository->create($data);

            return [
                'success' => true,
                'message' => 'Manba muvaffaqiyatli yaratildi',
                'data' => $otherSource,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'success' => false,
                'message' => 'Manba yaratishda xatolik yuz berdi',
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            $otherSource = $this->otherSourceRepository->findById($id);

            if (! $otherSource) {
                return [
                    'success' => false,
                    'message' => 'Manba topilmadi',
                ];
            }

            $this->otherSourceRepository->update($otherSource, $data);

            return [
                'success' => true,
                'message' => 'Manba muvaffaqiyatli yangilandi',
                'data' => $otherSource,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'success' => false,
                'message' => 'Manba yangilashda xatolik yuz berdi',
            ];
        }
    }

    public function invertActive(int $id): array
    {
        try {
            $otherSource = $this->otherSourceRepository->findById($id);

            if (! $otherSource) {
                return [
                    'success' => false,
                    'message' => 'Manba topilmadi',
                ];
            }

            $updatedOtherSource = $this->otherSourceRepository->invertActive($otherSource);

            return [
                'success' => true,
                'message' => 'Faollik holat muvaffaqiyatli o\'zgartirildi',
                'data' => $updatedOtherSource,
            ];
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'success' => false,
                'message' => 'Faol holatni o\'zgartirishda xatolik yuz berdi',
            ];
        }
    }

    public function findById(int $id): ?OtherSource
    {
        try {
            return $this->otherSourceRepository->findById($id);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return null;
        }
    }
}
