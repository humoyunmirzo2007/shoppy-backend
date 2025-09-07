<?php

namespace App\Modules\Warehouse\Requests;

use App\Http\Requests\MainRequest;

class GetSupplierCalculationsRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'sort' => ['nullable', 'array'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
            'filters' => ['nullable', 'array'],
            'filters.from_date' => ['nullable', 'date_format:d.m.Y'],
            'filters.to_date' => ['nullable', 'date_format:d.m.Y'],
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
            'filters.array' => 'Filterlar to\'g\'ri formatda yuborilishi kerak',
            'filters.from_date.date_format' => 'Boshlang\'ich sana d.m.Y formatida bo\'lishi kerak',
            'filters.to_date.date_format' => 'Tugash sanasi d.m.Y formatida bo\'lishi kerak',
        ];
    }
}
