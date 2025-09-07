<?php

namespace App\Modules\Trade\Services;

use App\Modules\Trade\Interfaces\ClientCalculationInterface;

class ClientCalculationService
{
    public function __construct(
        protected ClientCalculationInterface $clientCalculationRepository
    ) {}

    public function getByClientId(int $clientId, array $data)
    {
        return $this->clientCalculationRepository->getByClientId($clientId, $data);
    }
}
