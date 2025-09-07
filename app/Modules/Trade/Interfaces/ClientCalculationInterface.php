<?php

namespace App\Modules\Trade\Interfaces;

interface ClientCalculationInterface
{
    public function getByClientId(int $clientId, array $data);
}
