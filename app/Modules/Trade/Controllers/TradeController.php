<?php

namespace App\Modules\Trade\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Trade\Enums\TradeTypesEnum;
use App\Modules\Trade\Requests\GetTradeByIdRequest;
use App\Modules\Trade\Requests\GetTradesRequest;
use App\Modules\Trade\Requests\StoreTradeRequest;
use App\Modules\Trade\Requests\UpdateTradeRequest;
use App\Modules\Trade\Resources\TradeResource;
use App\Modules\Trade\Services\TradeService;

class TradeController extends Controller
{
    public function __construct(protected TradeService $tradeService) {}

    public function getTrades(GetTradesRequest $request)
    {
        $data = $this->tradeService->getByType(TradeTypesEnum::TRADE->value, $request->validated());

        return DefaultResource::collection($data);
    }

    public function getReturnProducts(GetTradesRequest $request)
    {
        $data = $this->tradeService->getByType(TradeTypesEnum::RETURN_PRODUCT->value, $request->validated());

        return DefaultResource::collection($data);
    }

    public function getByIdWithProducts(GetTradeByIdRequest $request, int $id)
    {
        return TradeResource::make($this->tradeService->getByIdWithProducts($id));
    }

    public function store(StoreTradeRequest $request)
    {
        $data = $this->tradeService->store($request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
            );
        }

        return Response::success(
            message: $data['message'],
            data: $data['data'],
            status: 201
        );
    }

    public function update(UpdateTradeRequest $request, int $id)
    {
        $data = $this->tradeService->update($id, $request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: $data['status_code'] ?? 422
            );
        }

        return Response::success(
            message: $data['message'],
            data: $data['data'],
        );
    }

    public function delete(GetTradeByIdRequest $request, int $id)
    {
        $data = $this->tradeService->delete($id);

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
            );
        }

        return Response::success(
            message: $data['message'],
        );
    }
}
