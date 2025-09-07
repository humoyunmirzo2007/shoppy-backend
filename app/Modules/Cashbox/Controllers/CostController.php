<?php

namespace App\Modules\Cashbox\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Cashbox\Requests\GetCostByIdRequest;
use App\Modules\Cashbox\Requests\GetCostsRequest;
use App\Modules\Cashbox\Requests\StoreCostRequest;
use App\Modules\Cashbox\Requests\UpdateCostRequest;
use App\Modules\Cashbox\Services\CostService;

class CostController
{
    public function __construct(protected CostService $costService) {}

    public function index(GetCostsRequest $request)
    {
        $result = $this->costService->getAllCosts($request->validated());

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return DefaultResource::collection($result['data']);
    }

    public function show(GetCostByIdRequest $request)
    {
        $result = $this->costService->getCostById($request->validated()['id']);

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return new DefaultResource($result['data']);
    }

    public function store(StoreCostRequest $request)
    {
        $result = $this->costService->createCost($request->validated());

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(new DefaultResource($result['data']), $result['message']);
    }

    public function update(UpdateCostRequest $request)
    {
        $data = $request->validated();
        $id = $data['id'];
        unset($data['id']);

        $result = $this->costService->updateCost($id, $data);

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(new DefaultResource($result['data']), $result['message']);
    }

    public function destroy(GetCostByIdRequest $request)
    {
        $result = $this->costService->deleteCost($request->validated()['id']);

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success([], $result['message']);
    }
}
