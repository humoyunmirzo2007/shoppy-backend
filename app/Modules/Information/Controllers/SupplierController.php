<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetSupplierByIdRequest;
use App\Modules\Information\Requests\GetSuppliersRequest;
use App\Modules\Information\Requests\StoreSupplierRequest;
use App\Modules\Information\Requests\UpdateSupplierRequest;
use App\Modules\Information\Services\SupplierService;

class SupplierController
{
    public function __construct(protected SupplierService $supplierService) {}

    public function getAll(GetSuppliersRequest $request)
    {
        $data = $this->supplierService->getAll($request->validated());

        return DefaultResource::collection($data);
    }

    public function getAllActive()
    {
        $data = $this->supplierService->getAllActive();

        return DefaultResource::collection($data);
    }

    public function store(StoreSupplierRequest $request)
    {
        $data = $this->supplierService->store($request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: 400
            );
        }

        return Response::success(
            $data['data'],
            $data['message'],
            201
        );
    }

    public function update(int $id, UpdateSupplierRequest $request)
    {
        $data = $this->supplierService->update($id, $request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: $data['status_code'] ?? 422
            );
        }

        return Response::success(
            $data['data'],
            $data['message']
        );
    }

    public function invertActive(GetSupplierByIdRequest $request, int $id)
    {
        $data = $this->supplierService->invertActive($id);

        return Response::success(
            $data['data'],
            $data['message']
        );
    }
}
