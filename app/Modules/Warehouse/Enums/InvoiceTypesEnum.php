<?php

namespace App\Modules\Warehouse\Enums;

enum InvoiceTypesEnum: string
{
    case SUPPLIER_INPUT = 'SUPPLIER_INPUT';
    case SUPPLIER_OUTPUT = 'SUPPLIER_OUTPUT';
    case OTHER_INPUT = 'OTHER_INPUT';
    case OTHER_OUTPUT = 'OTHER_OUTPUT';
}
