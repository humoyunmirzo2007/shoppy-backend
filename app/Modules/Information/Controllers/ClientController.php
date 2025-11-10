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

        if (! $data['success']) {
            return Response::error(
                message: $data['message'],
                status: 400
            );
        }

        return DefaultResource::collection($data['data']);
    }

    public function getAllActive()
    {
        $data = $this->clientService->getAllActive();

        if (! $data['success']) {
            return Response::error(
                message: $data['message'],
                status: 400
            );
        }

        return DefaultResource::collection($data['data']);
    }

    public function getAllWithDebt(GetClientsWithDebtRequest $request)
    {
        $data = $this->clientService->getAllWithDebt($request->validated());

        if (! $data['success']) {
            return Response::error(
                message: $data['message'],
                status: 400
            );
        }

        return ClientWithDebtResource::collection($data['data']);
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

    public function show(GetClientByIdRequest $request, int $id)
    {
        $data = $this->clientService->getById($id);

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
