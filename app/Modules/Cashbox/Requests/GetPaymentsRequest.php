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
            'search' => ['nullable', 'string'],
            'sort' => ['nullable', 'array'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
            'type' => ['sometimes', Rule::enum(PaymentTypesEnum::class)],
            'client_id' => 'sometimes|integer|exists:clients,id',
            'supplier_id' => 'sometimes|integer|exists:suppliers,id',
            'payment_type_id' => 'sometimes|integer|exists:payment_types,id',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
        ];
    }

    public function messages(): array
    {
        return [
            'search.string' => 'Qidiruv matni bo\'lishi kerak',
            'sort.array' => 'Saralash parametrlari noto\'g\'ri',
            'limit.integer' => 'Limit son bo\'lishi kerak',
            'limit.min' => 'Limit kamida 1 bo\'lishi kerak',
            'limit.max' => 'Limit ko\'pi bilan 50 bo\'lishi kerak',
            'page.integer' => 'Page son bo\'lishi kerak',
            'page.min' => 'Page kamida 1 bo\'lishi kerak',
            'type.enum' => 'To\'lov turi noto\'g\'ri. Quyidagi qiymatlardan birini tanlang: supplier_payment, client_payment, other_payment',

            'client_id.integer' => 'Mijoz ID si butun son bo\'lishi kerak',
            'client_id.exists' => 'Bunday mijoz mavjud emas',
            'supplier_id.integer' => 'Ta\'minotchi ID si butun son bo\'lishi kerak',
            'supplier_id.exists' => 'Bunday ta\'minotchi mavjud emas',
            'payment_type_id.integer' => 'To\'lov turi ID si butun son bo\'lishi kerak',
            'payment_type_id.exists' => 'Bunday to\'lov turi mavjud emas',
            'date_from.date' => 'Boshlanish sanasi to\'g\'ri formatda bo\'lishi kerak (YYYY-MM-DD)',
            'date_to.date' => 'Tugash sanasi to\'g\'ri formatda bo\'lishi kerak (YYYY-MM-DD)',
            'date_to.after_or_equal' => 'Tugash sanasi boshlanish sanasidan kech yoki teng bo\'lishi kerak',
        ];
    }
}
