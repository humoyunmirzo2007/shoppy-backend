<?php

namespace App\Modules\Cashbox\Enums;

enum CostTypesEnum: string
{
    case SUPPLIER_PAYMET_OUTPUT = 'SUPPLIER_PAYMET_OUTPUT';
    case CLIENT_PAYMET_OUTPUT = 'CLIENT_PAYMET_OUTPUT';
    case OTHER_PAYMET_OUTPUT = 'OTHER_PAYMET_OUTPUT';
}
