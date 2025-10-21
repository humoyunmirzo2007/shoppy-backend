<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetVariantAttributesRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'attribute_value_id' => ['nullable', 'integer', 'exists:attribute_values,id'],
            'sort' => ['nullable', 'array'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'variant_id.integer' => 'Variant ID si son bo\'lishi kerak',
            'variant_id.exists' => 'Tanlangan variant mavjud emas',
            'attribute_value_id.integer' => 'Atribut qiymati ID si son bo\'lishi kerak',
            'attribute_value_id.exists' => 'Tanlangan atribut qiymati mavjud emas',
            'sort.array' => 'Saralash parametrlari noto\'g\'ri',
            'limit.integer' => 'Limit son bo\'lishi kerak',
            'limit.min' => 'Limit kamida 1 bo\'lishi kerak',
            'limit.max' => 'Limit ko\'pi bilan 50 bo\'lishi kerak',
            'page.integer' => 'Page son bo\'lishi kerak',
            'page.min' => 'Page kamida 1 bo\'lishi kerak',
        ];
    }
}
