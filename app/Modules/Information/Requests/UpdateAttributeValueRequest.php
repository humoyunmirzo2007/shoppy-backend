<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeValueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attribute_id' => ['required', 'integer', 'exists:attributes,id'],
            'value_uz' => ['required', 'string', 'max:255'],
            'value_ru' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_id.required' => 'Atribut ID kiritilishi shart',
            'attribute_id.integer' => 'Atribut ID raqam bo\'lishi kerak',
            'attribute_id.exists' => 'Tanlangan atribut mavjud emas',
            'value_uz.required' => 'Atribut qiymati (o\'zbek) kiritilishi shart',
            'value_uz.string' => 'Atribut qiymati (o\'zbek) matn ko\'rinishida bo\'lishi kerak',
            'value_uz.max' => 'Atribut qiymati (o\'zbek) maksimal 255 belgidan oshmasligi kerak',
            'value_ru.required' => 'Atribut qiymati (rus) kiritilishi shart',
            'value_ru.string' => 'Atribut qiymati (rus) matn ko\'rinishida bo\'lishi kerak',
            'value_ru.max' => 'Atribut qiymati (rus) maksimal 255 belgidan oshmasligi kerak',
        ];
    }
}
