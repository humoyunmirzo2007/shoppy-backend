<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductGroupsRequest extends FormRequest
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
            'filters.brand_id' => ['nullable', 'integer', 'exists:brands,id'],
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
            'filters.brand_id.integer' => 'Brend ID raqam bo\'lishi kerak',
            'filters.brand_id.exists' => 'Tanlangan brend mavjud emas',
        ];
    }
}
