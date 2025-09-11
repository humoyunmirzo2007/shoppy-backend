<?php

namespace App\Modules\Warehouse\Enums;

enum SupplierCalculationTypesEnum: string
{
    case SUPPLIER_INPUT = 'SUPPLIER_INPUT';
    case SUPPLIER_OUTPUT = 'SUPPLIER_OUTPUT';
    case SUPPLIER_PAYMENT = 'SUPPLIER_PAYMET_INPUT';
}
