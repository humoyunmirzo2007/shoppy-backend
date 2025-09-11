<?php

namespace App\Modules\Cashbox\Enums;

enum PaymentTypesEnum: string
{
    case SUPPLIER_PAYMET_INPUT = 'SUPPLIER_PAYMET_INPUT';
    case CLIENT_PAYMET_INPUT = 'CLIENT_PAYMET_INPUT';
    case OTHER_PAYMET_INPUT = 'OTHER_PAYMET_INPUT';
    case TRANSFER = 'TRANSFER';
}
