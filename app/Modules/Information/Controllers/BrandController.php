<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetBrandByIdRequest;
use App\Modules\Information\Requests\GetBrandsRequest;
use App\Modules\Information\Requests\StoreBrandRequest;
use App\Modules\Information\Requests\UpdateBrandRequest;
use App\Modules\Information\Services\BrandService;

class BrandController extends Controller
{
    public function __construct(private BrandService $brandService) {}

    public function index(GetBrandsRequest $request)
    {

        $result = $this->brandService->getAll($request->validated());

        return DefaultResource::collection($result);
    }

    public function show(GetBrandByIdRequest $request, int $id)
    {
        $result = $this->brandService->getById($id);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return DefaultResource::make($result['data']);
    }

    public function store(StoreBrandRequest $request)
    {
        $result = $this->brandService->create($request->validated());

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success($result['message'], DefaultResource::make($result['data'])->resolve(), 201);
    }

    public function update(UpdateBrandRequest $request, int $id)
    {
        $result = $this->brandService->update($request->validated(), $id);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success($result['message'], DefaultResource::make($result['data'])->resolve());
    }

    public function invertActive(int $id)
    {
        $result = $this->brandService->invertActive($id);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success($result['message'], DefaultResource::make($result['data'])->resolve());
    }

    public function allActive()
    {
        $result = $this->brandService->allActive();

        return DefaultResource::collection($result);
    }
}
