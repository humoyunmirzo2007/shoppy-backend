<?php

namespace App\Modules\Trade\Services;

use App\Helpers\TelegramBot;
use App\Modules\Trade\Interfaces\ClientCalculationInterface;

class ClientCalculationService
{
    public function __construct(
        protected ClientCalculationInterface $clientCalculationRepository
    ) {}

    public function getByClientId(int $clientId, array $data)
    {
        try {
            return $this->clientCalculationRepository->getByClientId($clientId, $data);
        } catch (\Throwable $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'status' => 'error',
                'message' => 'Mijoz hisob-kitoblarini olishda xatolik yuz berdi',
            ];
        }
    }
}
