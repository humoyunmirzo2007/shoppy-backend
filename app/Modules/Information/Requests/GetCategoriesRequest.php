<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCategoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'array'],
            'filters' => ['nullable', 'array'],
            'filters.is_active' => ['nullable', 'boolean'],
            'filters.parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'filters.first_parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'limit.integer' => 'Har sahifadagi ma\'lumotlar soni butun son bo\'lishi kerak',
            'limit.min' => 'Har sahifadagi ma\'lumotlar soni minimal 1 bo\'lishi kerak',
            'limit.max' => 'Har sahifadagi ma\'lumotlar soni maksimal 100 bo\'lishi kerak',
            'sort.array' => 'Tartiblash parametri massiv bo\'lishi kerak',
            'filters.array' => 'Filtrlash parametri massiv bo\'lishi kerak',
            'filters.is_active.boolean' => 'Faollik holati mantiqiy qiymat bo\'lishi kerak',
            'filters.parent_id.integer' => 'Ota kategoriya ID raqam bo\'lishi kerak',
            'filters.parent_id.exists' => 'Tanlangan ota kategoriya mavjud emas',
            'filters.first_parent_id.integer' => 'Birinchi ota kategoriya ID raqam bo\'lishi kerak',
            'filters.first_parent_id.exists' => 'Tanlangan birinchi ota kategoriya mavjud emas',
        ];
    }
}
