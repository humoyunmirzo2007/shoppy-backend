<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\StoreAttributeValueRequest;
use App\Modules\Information\Requests\UpdateAttributeValueRequest;
use App\Modules\Information\Services\AttributeValueService;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function __construct(private AttributeValueService $attributeValueService) {}

    public function index(Request $request)
    {
        $data = [
            'search' => $request->get('search'),
            'limit' => $request->get('limit', 100),
            'sort' => $request->get('sort', ['id' => 'desc']),
            'filters' => $request->get('filters', []),
        ];

        $result = $this->attributeValueService->getAll($data);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return DefaultResource::collection($result['data']);
    }

    public function show(int $id)
    {
        $result = $this->attributeValueService->getById($id);

        return DefaultResource::make($result['data']);
    }

    public function getByAttributeId(int $attributeId)
    {
        $result = $this->attributeValueService->getByAttributeId($attributeId);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return DefaultResource::collection($result['data']);
    }

    public function store(StoreAttributeValueRequest $request)
    {
        $result = $this->attributeValueService->store($request->validated());

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success($result['message'], DefaultResource::make($result['data'])->resolve(), 201);
    }

    public function update(UpdateAttributeValueRequest $request, int $id)
    {
        $result = $this->attributeValueService->getById($id);

        if (! $result['success']) {
            return Response::error($result['message'], 404);
        }

        $attributeValue = $result['data'];

        $result = $this->attributeValueService->update($attributeValue, $request->validated());

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success($result['message'], DefaultResource::make($result['data'])->resolve());
    }
}
