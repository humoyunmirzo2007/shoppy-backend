<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetCostTypeByIdRequest;
use App\Modules\Information\Requests\GetCostTypesRequest;
use App\Modules\Information\Requests\StoreCostTypeRequest;
use App\Modules\Information\Requests\UpdateCostTypeRequest;
use App\Modules\Information\Services\CostTypeService;

class CostTypeController
{
    public function __construct(protected CostTypeService $costTypeService) {}

    public function getAll(GetCostTypesRequest $request)
    {
        $data = $this->costTypeService->getAll($request->validated());

        return DefaultResource::collection($data);
    }

    public function getAllActive()
    {
        $data = $this->costTypeService->getAllActive();

        return DefaultResource::collection($data);
    }

    public function store(StoreCostTypeRequest $request)
    {
        $data = $this->costTypeService->store($request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
            );
        }

        return Response::success(
            $data['data'],
            $data['message'],
            201
        );
    }

    public function update(int $id, UpdateCostTypeRequest $request)
    {
        $data = $this->costTypeService->update($id, $request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: $data['status_code'] ?? 400
            );
        }

        return Response::success(
            $data['data'],
            $data['message'],
        );
    }

    public function invertActive(GetCostTypeByIdRequest $request, int $id)
    {
        $data = $this->costTypeService->invertActive($id);

        return Response::success(
            $data['data'],
            $data['message'],
        );
    }

    public function show(GetCostTypeByIdRequest $request, int $id)
    {
        $data = $this->costTypeService->getById($id);

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: 404
            );
        }

        return Response::success(
            $data['data'],
            $data['message']
        );
    }
}
