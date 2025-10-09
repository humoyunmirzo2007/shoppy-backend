<?php

namespace App\Modules\Trade\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Trade\Requests\GetClientCalculationsRequest;
use App\Modules\Trade\Resources\ClientCalculationResource;
use App\Modules\Trade\Services\ClientCalculationService;

class ClientCalculationController extends Controller
{
    public function __construct(protected ClientCalculationService $clientCalculationService) {}

    public function getByClientId(GetClientCalculationsRequest $request, int $clientId)
    {
        $data = $this->clientCalculationService->getByClientId($clientId, $request->validated());

        return ClientCalculationResource::collection($data);
    }
}
