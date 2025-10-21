<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVariantAttributeRequest extends FormRequest
{
    /**
     * Request ni tasdiqlash huquqini tekshirish
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation qoidalari
     */
    public function rules(): array
    {
        return [
            'variant_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:product_variants,id',
            ],
            'attribute_value_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:attribute_values,id',
            ],
        ];
    }

    /**
     * Validation xabarlari
     */
    public function messages(): array
    {
        return [
            'variant_id.required' => 'Variant majburiy',
            'variant_id.integer' => 'Variant ID si son bo\'lishi kerak',
            'variant_id.exists' => 'Tanlangan variant mavjud emas',
            'attribute_value_id.required' => 'Atribut qiymati majburiy',
            'attribute_value_id.integer' => 'Atribut qiymati ID si son bo\'lishi kerak',
            'attribute_value_id.exists' => 'Tanlangan atribut qiymati mavjud emas',
        ];
    }
}
