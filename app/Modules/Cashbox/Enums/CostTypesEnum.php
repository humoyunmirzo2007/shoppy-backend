<?php

namespace App\Modules\Cashbox\Enums;

enum CostTypesEnum: string
{
    case SUPPLIER_COST = 'SUPPLIER_COST';
    case CLIENT_COST = 'CLIENT_COST';
    case OTHER_COST = 'OTHER_COST';
}
