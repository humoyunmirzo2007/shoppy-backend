<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Brend nomi kiritilishi shart',
            'name.string' => 'Brend nomi matn ko\'rinishida bo\'lishi kerak',
            'name.max' => 'Brend nomi maksimal 255 belgidan oshmasligi kerak',
            'name.unique' => 'Bu brend nomi allaqachon mavjud',
        ];
    }
}
