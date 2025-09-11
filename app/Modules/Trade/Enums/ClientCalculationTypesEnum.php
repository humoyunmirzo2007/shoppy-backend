<?php

namespace App\Modules\Trade\Enums;

enum ClientCalculationTypesEnum: string
{
    case TRADE = 'TRADE';
    case RETURN_PRODUCT = 'RETURN_PRODUCT';
    case CLIENT_PAYMENT = 'CLIENT_PAYMENT_INPUT';
}
