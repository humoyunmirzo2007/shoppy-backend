<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetPaymentTypesRequest;
use App\Modules\Information\Requests\GetPaymentTypeByIdRequest;
use App\Modules\Information\Requests\StorePaymentTypeRequest;
use App\Modules\Information\Requests\UpdatePaymentTypeRequest;
use App\Modules\Information\Services\PaymentTypeService;

class PaymentTypeController
{
    public function __construct(protected PaymentTypeService $paymentTypeService) {}

    public function index(GetPaymentTypesRequest $request)
    {
        $data = $this->paymentTypeService->index($request->validated());

        return DefaultResource::collection($data);
    }

    public function getAllActive()
    {
        $data = $this->paymentTypeService->getAllActive();

        return DefaultResource::collection($data);
    }

    public function store(StorePaymentTypeRequest $request)
    {
        $data = $this->paymentTypeService->store($request->validated());

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

    public function update(int $id, UpdatePaymentTypeRequest $request)
    {
        $data = $this->paymentTypeService->update($id, $request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: $data['status_code'] ?? 422
            );
        }

        return Response::success(
            $data['data'],
            $data['message'],
        );
    }

    public function invertActive(GetPaymentTypeByIdRequest $request, int $id)
    {
        $data = $this->paymentTypeService->invertActive($id);

        return Response::success(
            $data['data'],
            $data['message'],
        );
    }
}
