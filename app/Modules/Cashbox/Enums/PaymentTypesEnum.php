<?php

namespace App\Modules\Cashbox\Enums;

enum PaymentTypesEnum: string
{
    case SUPPLIER_PAYMENT_INPUT = 'SUPPLIER_PAYMENT_INPUT';
    case CLIENT_PAYMENT_INPUT = 'CLIENT_PAYMENT_INPUT';
    case OTHER_PAYMENT_INPUT = 'OTHER_PAYMENT_INPUT';
    case TRANSFER = 'TRANSFER';
}
