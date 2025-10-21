<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:attributes,name',
            ],
            'type' => [
                'required',
                'string',
                'in:select,text,number,textarea,checkbox,radio',
            ],
        ];
    }

    /**
     * Validation xabarlari
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Atribut nomi majburiy',
            'name.string' => 'Atribut nomi matn ko\'rinishida bo\'lishi kerak',
            'name.max' => 'Atribut nomi maksimal 255 ta belgi bo\'lishi kerak',
            'name.unique' => 'Bu nomdagi atribut allaqachon mavjud',
            'type.required' => 'Atribut turi majburiy',
            'type.string' => 'Atribut turi matn ko\'rinishida bo\'lishi kerak',
            'type.in' => 'Atribut turi quyidagilardan biri bo\'lishi kerak: select, text, number, textarea, checkbox, radio',
        ];
    }
}
