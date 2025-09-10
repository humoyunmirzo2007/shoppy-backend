<?php

namespace App\Modules\Cashbox\Enums;

enum PaymentTypesEnum: string
{
    case SUPPLIER_PAYMET_INPUTS = 'SUPPLIER_PAYMET_INPUTS';
    case CLIENT_PAYMET_INPUTS = 'CLIENT_PAYMET_INPUTS';
    case OTHER_PAYMET_INPUTS = 'OTHER_PAYMET_INPUTS';
    case TRANSFER = 'TRANSFER';
}
