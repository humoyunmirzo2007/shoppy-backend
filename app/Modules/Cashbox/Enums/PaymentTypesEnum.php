<?php

namespace App\Modules\Cashbox\Enums;

enum PaymentTypesEnum: string
{
    case SUPPLIER_PAYMENT = 'SUPPLIER_PAYMENT';
    case CLIENT_PAYMENT = 'CLIENT_PAYMENT';
    case OTHER_PAYMENT = 'OTHER_PAYMENT';
}
