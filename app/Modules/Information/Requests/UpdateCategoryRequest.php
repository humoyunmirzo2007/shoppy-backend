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
        $categoryId = $this->route('category')?->id ?? $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($categoryId)],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id', Rule::notIn([$categoryId])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Kategoriya nomi kiritilishi shart',
            'name.string' => 'Kategoriya nomi matn ko\'rinishida bo\'lishi kerak',
            'name.max' => 'Kategoriya nomi maksimal 255 belgidan oshmasligi kerak',
            'name.unique' => 'Bu kategoriya nomi allaqachon mavjud',
            'description.string' => 'Kategoriya tavsifi matn ko\'rinishida bo\'lishi kerak',
            'parent_id.integer' => 'Ota kategoriya ID raqam bo\'lishi kerak',
            'parent_id.exists' => 'Tanlangan ota kategoriya mavjud emas',
            'parent_id.not_in' => 'Kategoriya o\'zini ota kategoriya sifatida tanlash mumkin emas',
            'sort_order.integer' => 'Tartib raqami butun son bo\'lishi kerak',
            'sort_order.min' => 'Tartib raqami 0 yoki undan katta bo\'lishi kerak',
        ];
    }
}
