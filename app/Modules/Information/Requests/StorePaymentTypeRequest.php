<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;
use App\Modules\Information\Enums\PaymentTypeCurrencyEnum;

class StorePaymentTypeRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                'unique:payment_types',
            ],
            'currency' => [
                'required',
                'string',
                'in:' . implode(',', array_column(PaymentTypeCurrencyEnum::cases(), 'value')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'To\'lov turi nomini kiritish majburiy',
            'name.unique' => 'Bu to\'lov turi nomi allaqachon ro\'yxatdan o\'tgan',
            'name.max' => 'To\'lov turi nomi 255 ta belgidan oshmasligi kerak',
            'currency.required' => 'Valyuta kiritish majburiy',
            'currency.string' => 'Valyuta matn bo\'lishi kerak',
            'currency.in' => 'Valyuta UZS yoki USD bo\'lishi kerak'
        ];
    }
}
