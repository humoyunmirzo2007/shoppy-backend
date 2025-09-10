<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetClientByIdRequest;
use App\Modules\Information\Requests\GetClientsRequest;
use App\Modules\Information\Requests\GetClientsWithDebtRequest;
use App\Modules\Information\Requests\StoreClientRequest;
use App\Modules\Information\Requests\UpdateClientRequest;
use App\Modules\Information\Resources\ClientWithDebtResource;
use App\Modules\Information\Services\ClientService;

class ClientController
{
    public function __construct(protected ClientService $clientService) {}

    public function getAll(GetClientsRequest $request)
    {
        $data = $this->clientService->getAll($request->validated());

        return DefaultResource::collection($data);
    }

    public function getAllActive()
    {
        $data = $this->clientService->getAllActive();

        return DefaultResource::collection($data);
    }

    public function getAllWithDebt(GetClientsWithDebtRequest $request)
    {
        $data = $this->clientService->getAllWithDebt($request->validated());

        return ClientWithDebtResource::collection($data);
    }

    public function store(StoreClientRequest $request)
    {
        $data = $this->clientService->store($request->validated());

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

    public function update(int $id, UpdateClientRequest $request)
    {
        $data = $this->clientService->update($id, $request->validated());

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

    public function invertActive(GetClientByIdRequest $request, int $id)
    {
        $data = $this->clientService->invertActive($id);

        return Response::success(
            $data['data'],
            $data['message']
        );
    }
}
