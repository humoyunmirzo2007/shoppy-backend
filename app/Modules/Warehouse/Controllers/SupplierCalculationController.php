<?php

namespace App\Modules\Warehouse\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Warehouse\Requests\GetSupplierCalculationsRequest;
use App\Modules\Warehouse\Resources\SupplierCalculationResource;
use App\Modules\Warehouse\Services\SupplierCalculationService;

class SupplierCalculationController extends Controller
{
    public function __construct(protected SupplierCalculationService $supplierCalculationService) {}

    public function getBySupplierId(GetSupplierCalculationsRequest $request, int $supplierId)
    {
        $data = $this->supplierCalculationService->getBySupplierId($supplierId, $request->validated());

        return SupplierCalculationResource::collection($data);
    }
}
