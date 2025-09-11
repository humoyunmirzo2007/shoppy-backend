<?php

namespace App\Modules\Cashbox\Enums;

enum OtherCalculationTypesEnum: string
{
    case OTHER_PAYMENT = 'OTHER_PAYMENT_INPUT';
    case OTHER_COST = 'OTHER_PAYMENT_OUTPUT';
    case OTHER_PRODUCT_INPUT = 'OTHER_PRODUCT_INPUT';
    case OTHER_PRODUCT_OUTPUT = 'OTHER_PRODUCT_OUTPUT';
}
