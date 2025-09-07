<?php

namespace App\Modules\Cashbox\Enums;

enum PaymentTypesEnum: string
{
    case SUPPLIER_PAYMENT = 'supplier_payment';
    case CLIENT_PAYMENT = 'client_payment';
    case OTHER_PAYMENT = 'other_payment';
}
