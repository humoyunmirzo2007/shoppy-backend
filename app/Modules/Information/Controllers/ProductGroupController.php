<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetProductGroupByIdRequest;
use App\Modules\Information\Requests\GetProductGroupsRequest;
use App\Modules\Information\Requests\UpdateProductGroupRequest;
use App\Modules\Information\Services\ProductGroupService;

class ProductGroupController extends Controller
{
    public function __construct(private ProductGroupService $productGroupService) {}

    /**
     * Barcha mahsulot guruhlarini olish
     */
    public function index(GetProductGroupsRequest $request)
    {
        $data = $request->validated();

        $result = $this->productGroupService->getAll($data, ['*']);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return DefaultResource::collection($result['data']);
    }

    /**
     * ID bo'yicha mahsulot guruhini olish
     */
    public function show(GetProductGroupByIdRequest $request, int $id)
    {
        $result = $this->productGroupService->getById($id, ['*']);

        if (! $result['success']) {
            return Response::error($result['message'], 404);
        }

        return DefaultResource::make($result['data']);
    }

    /**
     * Mahsulot guruhini yangilash
     */
    public function update(UpdateProductGroupRequest $request, int $id)
    {
        $data = $request->validated();
        $result = $this->productGroupService->update($id, $data);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success(message: $result['message'], data: DefaultResource::make($result['data'])->resolve());
    }
}
