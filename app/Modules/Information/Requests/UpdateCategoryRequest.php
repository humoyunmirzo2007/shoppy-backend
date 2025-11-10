<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name_uz' => ['required', 'string', 'max:255', Rule::unique('categories', 'name_uz')->ignore($id)],
            'name_ru' => ['required', 'string', 'max:255', Rule::unique('categories', 'name_ru')->ignore($id)],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id', Rule::notIn([$id])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name_uz.required' => 'Kategoriya nomi (o\'zbek) kiritilishi shart',
            'name_uz.string' => 'Kategoriya nomi (o\'zbek) matn ko\'rinishida bo\'lishi kerak',
            'name_uz.max' => 'Kategoriya nomi (o\'zbek) maksimal 255 belgidan oshmasligi kerak',
            'name_uz.unique' => 'Bu kategoriya nomi (o\'zbek) allaqachon mavjud',
            'name_ru.required' => 'Kategoriya nomi (rus) kiritilishi shart',
            'name_ru.string' => 'Kategoriya nomi (rus) matn ko\'rinishida bo\'lishi kerak',
            'name_ru.max' => 'Kategoriya nomi (rus) maksimal 255 belgidan oshmasligi kerak',
            'name_ru.unique' => 'Bu kategoriya nomi (rus) allaqachon mavjud',
            'description.string' => 'Kategoriya tavsifi matn ko\'rinishida bo\'lishi kerak',
            'parent_id.integer' => 'Ota kategoriya ID raqam bo\'lishi kerak',
            'parent_id.exists' => 'Tanlangan ota kategoriya mavjud emas',
            'parent_id.not_in' => 'Kategoriya o\'zini ota kategoriya sifatida tanlash mumkin emas',
            'sort_order.integer' => 'Tartib raqami butun son bo\'lishi kerak',
            'sort_order.min' => 'Tartib raqami 0 yoki undan katta bo\'lishi kerak',
        ];
    }
}
