<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Http\Resources\DefaultResource;
use App\Modules\Information\Requests\GetProductByIdRequest;
use App\Modules\Information\Requests\GetProductsRequest;
use App\Modules\Information\Requests\StoreProductRequest;
use App\Modules\Information\Requests\UpdateProductRequest;
use App\Modules\Information\Requests\UploadProductsImportFileRequest;
use App\Modules\Information\Services\ProductService;

class ProductController
{
    public function __construct(protected ProductService $productService) {}

    public function getAll(GetProductsRequest $request)
    {
        $data = $this->productService->getAll($request->validated());

        return DefaultResource::collection($data);
    }

    public function getForResidues(GetProductsRequest $request)
    {
        $data = $this->productService->getForResidues($request->validated());

        return DefaultResource::collection($data);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $this->productService->store($request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
            );
        }

        return Response::success(
            $data['data'],
            $data['message'],
            201
        );
    }

    public function update(int $id, UpdateProductRequest $request)
    {
        $data = $this->productService->update($id, $request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: $data['status_code'] ?? 400
            );
        }

        return Response::success(
            $data['data'],
            $data['message'],
        );
    }

    public function invertActive(GetProductByIdRequest $request, int $id)
    {
        $data = $this->productService->invertActive($id);

        return Response::success(
            $data['data'],
            $data['message'],
        );
    }

    public function downloadTemplate()
    {
        return $this->productService->downloadTemplate();
    }

    public function downloadUpdatePriceTemplate()
    {
        return $this->productService->downloadUpdatePriceTemplate();
    }

    public function import(UploadProductsImportFileRequest $request)
    {
        $data = $this->productService->import($request->file('file'));

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],

            );
        }

        return Response::success(
            message: $data['message']
        );
    }


    public function updatePricesFromTemplate(UploadProductsImportFileRequest $request)
    {
        $data = $this->productService->updatePricesFromTemplate($request->file('file'));

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: $data['status_code'] ?? 400
            );
        }

        return Response::success(
            message: $data['message']
        );
    }
}
