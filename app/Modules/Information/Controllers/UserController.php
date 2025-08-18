<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\UpdateUserPasswordRequest;
use App\Modules\Information\Requests\GetUserByIdRequest;
use App\Modules\Information\Requests\GetUsersRequest;
use App\Modules\Information\Requests\StoreUserRequest;
use App\Modules\Information\Requests\UpdateUserRequest;
use App\Modules\Information\Services\UserService;

class UserController
{
    public function __construct(protected UserService $userService) {}

    public function index(GetUsersRequest $request)
    {
        $data = $this->userService->index($request->validated());

        return DefaultResource::collection($data);
    }

    public function getAll()
    {
        $data = $this->userService->getAll();

        return DefaultResource::collection($data);
    }


    public function store(StoreUserRequest $request)
    {
        $data = $this->userService->store($request->validated());

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

    public function update(int $id, UpdateUserRequest $request)
    {
        $data = $this->userService->update($id, $request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: $data['status_code'] ?? 400
            );
        }

        return Response::success(
            $data['data'],
            $data['message']
        );
    }

    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $data = $this->userService->updatePassword($request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message']
            );
        }

        return Response::success(
            message: $data['message']
        );
    }

    public function invertActive(GetUserByIdRequest $request, int $id)
    {
        $data = $this->userService->invertActive($id);

        return Response::success(
            $data['data'],
            $data['message']
        );
    }
}
