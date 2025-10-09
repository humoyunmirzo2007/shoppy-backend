<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetProductsRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'sort' => ['nullable', 'array'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
            'filters' => ['nullable', 'array'],
            'filters.category_id' => ['nullable', 'integer', 'exists:categories,id'],

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
            'filters.category_id.integer' => 'Kategoriya ID son bo\'lishi kerak',
            'filters.category_id.exists' => 'Bunday kategoriya mavjud emas',
        ];
    }
}
