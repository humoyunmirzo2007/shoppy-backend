<?php

namespace App\Modules\Warehouse\Controllers;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use App\Modules\Warehouse\Enums\InvoiceTypesEnum;
use App\Modules\Warehouse\Requests\GetInvoiceByIdRequest;
use App\Modules\Warehouse\Requests\GetInvoicesRequest;
use App\Modules\Warehouse\Requests\StoreInvoiceRequest;
use App\Modules\Warehouse\Requests\UpdateInvoiceRequest;
use App\Modules\Warehouse\Resources\InvoiceResource;
use App\Modules\Warehouse\Services\InvoiceService;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function getInputs(GetInvoicesRequest $request)
    {
        $types = [InvoiceTypesEnum::SUPPLIER_INPUT->value, InvoiceTypesEnum::OTHER_INPUT->value];
        $data = $this->invoiceService->getByTypes($types, $request->validated());

        return DefaultResource::collection($data);
    }

    public function getOutputs(GetInvoicesRequest $request)
    {
        $types = [InvoiceTypesEnum::SUPPLIER_OUTPUT->value, InvoiceTypesEnum::OTHER_OUTPUT->value];
        $data = $this->invoiceService->getByTypes($types, $request->validated());

        return DefaultResource::collection($data);
    }

    public function getByIdWithProducts(GetInvoiceByIdRequest $request, int $id)
    {
        return InvoiceResource::make($this->invoiceService->getByIdWithProducts($id));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $data = $this->invoiceService->store($request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
            );
        }

        return Response::success(
            message: $data['message'],
            data: $data['data'],
            status: 201
        );
    }

    public function update(UpdateInvoiceRequest $request, int $id)
    {
        $data = $this->invoiceService->update($id, $request->validated());

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
                status: $data['status_code'] ?? 422
            );
        }

        return Response::success(
            message: $data['message'],
            data: $data['data'],
        );
    }

    public function delete(GetInvoiceByIdRequest $request, int $id)
    {
        $data = $this->invoiceService->delete($id);

        if ($data['status'] === 'error') {
            return Response::error(
                message: $data['message'],
            );
        }

        return Response::success(
            message: $data['message'],
        );
    }
}
