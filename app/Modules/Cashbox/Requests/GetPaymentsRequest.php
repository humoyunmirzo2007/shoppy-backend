<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;
use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use Illuminate\Validation\Rule;

class GetPaymentsRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'type' => ['sometimes', Rule::enum(PaymentTypesEnum::class)],
            'cashbox_id' => 'sometimes|integer|exists:cashboxes,id',
            'client_id' => 'sometimes|integer|exists:clients,id',
            'supplier_id' => 'sometimes|integer|exists:suppliers,id',
            'payment_type_id' => 'sometimes|integer|exists:payment_types,id',
            'status' => 'sometimes|string',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
        ];
    }

    public function messages(): array
    {
        return [
            'type.enum' => 'To\'lov turi noto\'g\'ri. Quyidagi qiymatlardan birini tanlang: supplier_payment, client_payment, other_payment',
            'cashbox_id.integer' => 'Kassa ID si butun son bo\'lishi kerak',
            'cashbox_id.exists' => 'Bunday kassa mavjud emas',
            'client_id.integer' => 'Mijoz ID si butun son bo\'lishi kerak',
            'client_id.exists' => 'Bunday mijoz mavjud emas',
            'supplier_id.integer' => 'Ta\'minotchi ID si butun son bo\'lishi kerak',
            'supplier_id.exists' => 'Bunday ta\'minotchi mavjud emas',
            'payment_type_id.integer' => 'To\'lov turi ID si butun son bo\'lishi kerak',
            'payment_type_id.exists' => 'Bunday to\'lov turi mavjud emas',
            'status.string' => 'To\'lov holati matn ko\'rinishida bo\'lishi kerak',
            'date_from.date' => 'Boshlanish sanasi to\'g\'ri formatda bo\'lishi kerak (YYYY-MM-DD)',
            'date_to.date' => 'Tugash sanasi to\'g\'ri formatda bo\'lishi kerak (YYYY-MM-DD)',
            'date_to.after_or_equal' => 'Tugash sanasi boshlanish sanasidan kech yoki teng bo\'lishi kerak',
        ];
    }
}
