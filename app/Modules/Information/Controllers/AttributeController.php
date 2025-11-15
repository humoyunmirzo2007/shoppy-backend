<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\StoreAttributeRequest;
use App\Modules\Information\Requests\UpdateAttributeRequest;
use App\Modules\Information\Services\AttributeService;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(private AttributeService $attributeService) {}

    public function index(Request $request)
    {
        $data = [
            'search' => $request->get('search'),
            'limit' => $request->get('limit', 100),
            'sort' => $request->get('sort', ['id' => 'desc']),
            'filters' => $request->get('filters', []),
        ];

        $result = $this->attributeService->getAll($data);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return DefaultResource::collection($result['data']);
    }

    public function show(int $id)
    {
        $result = $this->attributeService->getById($id);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return DefaultResource::make($result['data']);
    }

    public function store(StoreAttributeRequest $request)
    {
        $result = $this->attributeService->store($request->validated());

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success($result['message'], DefaultResource::make($result['data'])->resolve(), 201);
    }

    public function update(UpdateAttributeRequest $request, int $id)
    {
        $result = $this->attributeService->getById($id);

        if (! $result['success']) {
            return Response::error($result['message'], 404);
        }

        $attribute = $result['data'];

        $result = $this->attributeService->update($attribute, $request->validated());

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success($result['message'], DefaultResource::make($result['data'])->resolve());
    }

    public function invertActive(int $id)
    {
        $result = $this->attributeService->invertActive($id);

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return Response::success($result['message'], DefaultResource::make($result['data'])->resolve());
    }

    public function allActive()
    {
        $result = $this->attributeService->allActive();

        if (! $result['success']) {
            return Response::error($result['message']);
        }

        return DefaultResource::collection($result['data']);
    }
}
