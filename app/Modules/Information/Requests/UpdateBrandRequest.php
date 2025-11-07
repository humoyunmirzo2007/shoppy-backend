<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('brands', 'name')->ignore($brandId)],
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
