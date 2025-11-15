<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_uz' => ['required', 'string', 'max:255', 'unique:attributes,name_uz'],
            'name_ru' => ['required', 'string', 'max:255', 'unique:attributes,name_ru'],
        ];
    }

    public function messages(): array
    {
        return [
            'name_uz.required' => 'Atribut nomi (o\'zbek) kiritilishi shart',
            'name_uz.string' => 'Atribut nomi (o\'zbek) matn ko\'rinishida bo\'lishi kerak',
            'name_uz.max' => 'Atribut nomi (o\'zbek) maksimal 255 belgidan oshmasligi kerak',
            'name_uz.unique' => 'Bu atribut nomi (o\'zbek) allaqachon mavjud',
            'name_ru.required' => 'Atribut nomi (rus) kiritilishi shart',
            'name_ru.string' => 'Atribut nomi (rus) matn ko\'rinishida bo\'lishi kerak',
            'name_ru.max' => 'Atribut nomi (rus) maksimal 255 belgidan oshmasligi kerak',
            'name_ru.unique' => 'Bu atribut nomi (rus) allaqachon mavjud',
        ];
    }
}
