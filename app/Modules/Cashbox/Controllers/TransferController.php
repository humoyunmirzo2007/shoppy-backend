<?php

namespace App\Modules\Cashbox\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use App\Modules\Cashbox\Requests\GetTransferByIdRequest;
use App\Modules\Cashbox\Requests\GetTransfersRequest;
use App\Modules\Cashbox\Requests\StoreTransferRequest;
use App\Modules\Cashbox\Services\TransferService;
use App\Modules\Cashbox\Resources\TransferResource;
use App\Helpers\Response;

class TransferController extends Controller
{
    public function __construct(protected TransferService $transferService) {}

    /**
     * Display a listing of transfers.
     */
    public function index(GetTransfersRequest $request)
    {
        $result = $this->transferService->getTransfers($request->validated());

        if (!$result['success']) {
            return Response::error($result['message'], 400);
        }

        return  DefaultResource::collection($result['data']);

        return $transfers;
    }

    /**
     * Store a newly created transfer.
     */
    public function store(StoreTransferRequest $request)
    {
        $result = $this->transferService->createTransfer($request->validated());

        if (!$result['success']) {
            return Response::error($result['message'], 400);
        }

        return Response::success(DefaultResource::make($result['data']), $result['message'], 201);
    }

    /**
     * Display the specified transfer.
     */
    public function show(GetTransferByIdRequest $request)
    {
        $result = $this->transferService->getTransferById($request->validated()['id']);

        if (!$result['success']) {
            return Response::error($result['message'], 404);
        }

        return Response::success(new TransferResource($result['data']), $result['message']);
    }

    /**
     * Remove the specified transfer.
     */
    public function destroy(GetTransferByIdRequest $request)
    {
        $result = $this->transferService->deleteTransfer($request->validated()['id']);

        if (!$result['success']) {
            return Response::error($result['message'], 400);
        }

        return Response::success(null, $result['message']);
    }
}
