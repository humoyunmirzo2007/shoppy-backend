<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeValueRequest extends FormRequest
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
            'attribute_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:attributes,id',
            ],
            'value' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Validation xabarlari
     */
    public function messages(): array
    {
        return [
            'attribute_id.required' => 'Atribut majburiy',
            'attribute_id.integer' => 'Atribut ID si son bo\'lishi kerak',
            'attribute_id.exists' => 'Tanlangan atribut mavjud emas',
            'value.required' => 'Atribut qiymati majburiy',
            'value.string' => 'Atribut qiymati matn ko\'rinishida bo\'lishi kerak',
            'value.max' => 'Atribut qiymati maksimal 255 ta belgi bo\'lishi kerak',
        ];
    }
}
