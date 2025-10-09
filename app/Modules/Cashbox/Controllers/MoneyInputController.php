<?php

namespace App\Modules\Cashbox\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Cashbox\Requests\GetPaymentByIdRequest;
use App\Modules\Cashbox\Requests\GetPaymentsRequest;
use App\Modules\Cashbox\Requests\StorePaymentRequest;
use App\Modules\Cashbox\Requests\UpdatePaymentRequest;
use App\Modules\Cashbox\Services\MoneyInputService;

class MoneyInputController
{
    public function __construct(protected MoneyInputService $moneyInputService) {}

    public function index(GetPaymentsRequest $request)
    {
        $result = $this->moneyInputService->getAllMoneyInputs($request->validated());

        if (! $result['success']) {
            return Response::error([], $result['message']);
        }

        return DefaultResource::collection($result['data']);
    }

    public function show(GetPaymentByIdRequest $request)
    {
        $result = $this->moneyInputService->getMoneyInputById($request->validated()['id']);

        if (! $result['success']) {
            return Response::error([], $result['message']);
        }

        return new DefaultResource($result['data']);
    }

    public function store(StorePaymentRequest $request)
    {
        $result = $this->moneyInputService->createMoneyInput($request->validated());

        if (! $result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(new DefaultResource($result['data']), $result['message']);
    }

    public function update(UpdatePaymentRequest $request)
    {
        $data = $request->validated();
        $id = $data['id'];
        unset($data['id']);

        $result = $this->moneyInputService->updateMoneyInput($id, $data);

        if (! $result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(new DefaultResource($result['data']), $result['message']);
    }

    public function destroy(GetPaymentByIdRequest $request)
    {
        $result = $this->moneyInputService->deleteMoneyInput($request->validated()['id']);

        if (! $result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success([], $result['message']);
    }
}
