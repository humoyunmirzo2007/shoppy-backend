<?php

namespace App\Modules\Cashbox\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cashbox\Requests\GetCashboxesRequest;
use App\Modules\Cashbox\Requests\GetCashboxByIdRequest;
use App\Modules\Cashbox\Requests\StoreCashboxRequest;
use App\Modules\Cashbox\Resources\CashboxResource;
use App\Modules\Cashbox\Services\CashboxService;
use App\Helpers\Response;
use Illuminate\Http\JsonResponse;

class CashboxController extends Controller
{
    public function __construct(protected CashboxService $cashboxService) {}

    public function index(GetCashboxesRequest $request): JsonResponse
    {
        $result = $this->cashboxService->getAllCashboxes($request->validated());

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(
            CashboxResource::collection($result['data']),
            $result['message']
        );
    }

    public function show(GetCashboxByIdRequest $request): JsonResponse
    {
        $result = $this->cashboxService->getCashboxById($request->validated()['id']);

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(
            new CashboxResource($result['data']),
            $result['message']
        );
    }

    public function store(StoreCashboxRequest $request): JsonResponse
    {
        $result = $this->cashboxService->createCashbox($request->validated());

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(
            new CashboxResource($result['data']),
            $result['message'],
            201
        );
    }

    public function toggleActive(GetCashboxByIdRequest $request): JsonResponse
    {
        $result = $this->cashboxService->toggleCashboxActive($request->validated()['id']);

        if (!$result['success']) {
            return Response::error([], $result['message']);
        }

        return Response::success(
            new CashboxResource($result['data']),
            $result['message']
        );
    }
}
