<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attributeId = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('attributes', 'name')->ignore($attributeId)],
            'type' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Atribut nomi kiritilishi shart',
            'name.string' => 'Atribut nomi matn ko\'rinishida bo\'lishi kerak',
            'name.max' => 'Atribut nomi maksimal 255 belgidan oshmasligi kerak',
            'name.unique' => 'Bu atribut nomi allaqachon mavjud',
            'type.required' => 'Atribut turi kiritilishi shart',
            'type.string' => 'Atribut turi matn ko\'rinishida bo\'lishi kerak',
            'type.max' => 'Atribut turi maksimal 255 belgidan oshmasligi kerak',
        ];
    }
}
