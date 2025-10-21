<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetAttributeValuesRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'attribute_id' => ['nullable', 'integer', 'exists:attributes,id'],
            'sort' => ['nullable', 'array'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_id.integer' => 'Atribut ID si son bo\'lishi kerak',
            'attribute_id.exists' => 'Tanlangan atribut mavjud emas',
            'sort.array' => 'Saralash parametrlari noto\'g\'ri',
            'limit.integer' => 'Limit son bo\'lishi kerak',
            'limit.min' => 'Limit kamida 1 bo\'lishi kerak',
            'limit.max' => 'Limit ko\'pi bilan 50 bo\'lishi kerak',
            'page.integer' => 'Page son bo\'lishi kerak',
            'page.min' => 'Page kamida 1 bo\'lishi kerak',
        ];
    }
}
