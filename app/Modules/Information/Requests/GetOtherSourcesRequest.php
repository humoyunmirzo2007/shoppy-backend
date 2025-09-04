<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetOtherSourcesRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'sort' => ['nullable', 'array'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
            'filters' => ['nullable', 'array'],
            'filters.type' => ['nullable', 'string', 'in:PRODUCT,PAYMENT'],
            'filters.is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'sort.array' => 'Saralash parametrlari noto\'g\'ri',
            'limit.integer' => 'Limit son bo\'lishi kerak',
            'limit.min' => 'Limit kamida 1 bo\'lishi kerak',
            'limit.max' => 'Limit ko\'pi bilan 50 bo\'lishi kerak',
            'page.integer' => 'Page son bo\'lishi kerak',
            'page.min' => 'Page kamida 1 bo\'lishi kerak',
            'filters.type.string' => 'Turi matn bo\'lishi kerak',
            'filters.type.in' => 'Turi PRODUCT yoki PAYMENT bo\'lishi kerak',
            'filters.is_active.boolean' => 'Faol holat mantiqiy qiymat bo\'lishi kerak'
        ];
    }
}
