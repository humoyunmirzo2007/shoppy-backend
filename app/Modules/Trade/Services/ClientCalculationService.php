<?php

namespace App\Modules\Trade\Services;

use App\Helpers\TelegramBugNotifier;
use App\Modules\Trade\Interfaces\ClientCalculationInterface;

class ClientCalculationService
{
    public function __construct(
        protected ClientCalculationInterface $clientCalculationRepository,
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    public function getByClientId(int $clientId, array $data)
    {
        try {
            return $this->clientCalculationRepository->getByClientId($clientId, $data);
        } catch (\Throwable $e) {
            $this->telegramNotifier->sendError($e, request());

            return [
                'status' => 'error',
                'message' => 'Mijoz hisob-kitoblarini olishda xatolik yuz berdi',
            ];
        }
    }
}
