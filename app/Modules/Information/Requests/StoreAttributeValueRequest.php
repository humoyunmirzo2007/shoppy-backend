<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attribute_id' => ['required', 'integer', 'exists:attributes,id'],
            'value' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_id.required' => 'Atribut ID kiritilishi shart',
            'attribute_id.integer' => 'Atribut ID raqam bo\'lishi kerak',
            'attribute_id.exists' => 'Tanlangan atribut mavjud emas',
            'value.required' => 'Atribut qiymati kiritilishi shart',
            'value.string' => 'Atribut qiymati matn ko\'rinishida bo\'lishi kerak',
            'value.max' => 'Atribut qiymati maksimal 255 belgidan oshmasligi kerak',
        ];
    }
}
