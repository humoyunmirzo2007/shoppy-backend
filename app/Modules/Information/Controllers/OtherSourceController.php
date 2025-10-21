<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetOtherSourcesByTypeRequest;
use App\Modules\Information\Requests\GetOtherSourcesRequest;
use App\Modules\Information\Requests\StoreOtherSourceRequest;
use App\Modules\Information\Requests\UpdateOtherSourceRequest;
use App\Modules\Information\Services\OtherSourceService;

class OtherSourceController
{
    public function __construct(protected OtherSourceService $otherSourceService) {}

    public function getAll(GetOtherSourcesRequest $request)
    {
        $data = $this->otherSourceService->getAll($request->validated());

        return DefaultResource::collection($data);
    }

    public function getByTypeAllActive(GetOtherSourcesByTypeRequest $request)
    {
        $data = $this->otherSourceService->getByTypeAllActive($request->validated());

        return DefaultResource::collection($data);
    }

    public function store(StoreOtherSourceRequest $request)
    {
        $data = $this->otherSourceService->create($request->validated());

        if ($data['success'] === false) {
            return Response::error(
                message: $data['message'] ?? ''
            );
        }

        return Response::success(
            $data['data'] ?? [],
            $data['message'] ?? '',
        );
    }

    public function update(int $id, UpdateOtherSourceRequest $request)
    {
        $data = $this->otherSourceService->update($id, $request->validated());

        if ($data['success'] === false) {
            return Response::error(
                message: $data['message'] ?? ''
            );
        }

        return Response::success(
            $data['data'] ?? [],
            $data['message'] ?? '',
        );
    }

    public function invertActive(int $id)
    {
        $data = $this->otherSourceService->invertActive($id);

        if ($data['success'] === false) {
            return Response::error(
                message: $data['message'] ?? ''
            );
        }

        return Response::success(
            $data['data'] ?? [],
            $data['message'] ?? '',
        );
    }

    public function show(int $id)
    {
        $data = $this->otherSourceService->getById($id);

        if ($data['success'] === false) {
            return Response::error(
                message: $data['message'] ?? '',
                status: 404
            );
        }

        return Response::success(
            $data['data'] ?? [],
            $data['message'] ?? ''
        );
    }
}
