<?php

namespace App\Modules\Warehouse\Enums;

enum InvoiceTypesEnum: string
{
    case SUPPLIER_INPUT = 'SUPPLIER_INPUT';
    case SUPPLIER_OUTPUT = 'SUPPLIER_OUTPUT';
}
