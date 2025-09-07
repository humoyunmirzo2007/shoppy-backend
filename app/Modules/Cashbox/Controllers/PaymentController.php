<?php

namespace App\Modules\Cashbox\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Cashbox\Requests\GetPaymentByIdRequest;
use App\Modules\Cashbox\Requests\GetPaymentsRequest;
use App\Modules\Cashbox\Requests\StorePaymentRequest;
use App\Modules\Cashbox\Requests\UpdatePaymentRequest;
use App\Modules\Cashbox\Services\PaymentService;

class PaymentController
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index(GetPaymentsRequest $request)
    {
        $result = $this->paymentService->getAllPayments($request->validated());

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return DefaultResource::collection($result['data']);
    }

    public function show(GetPaymentByIdRequest $request)
    {
        $result = $this->paymentService->getPaymentById($request->validated()['id']);

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return new DefaultResource($result['data']);
    }

    public function store(StorePaymentRequest $request)
    {
        $result = $this->paymentService->createPayment($request->validated());

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(new DefaultResource($result['data']), $result['message']);
    }

    public function update(UpdatePaymentRequest $request)
    {
        $data = $request->validated();
        $id = $data['id'];
        unset($data['id']);

        $result = $this->paymentService->updatePayment($id, $data);

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(new DefaultResource($result['data']), $result['message']);
    }

    public function destroy(GetPaymentByIdRequest $request)
    {
        $result = $this->paymentService->deletePayment($request->validated()['id']);

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success([], $result['message']);
    }
}
