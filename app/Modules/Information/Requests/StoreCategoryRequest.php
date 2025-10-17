<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
                'unique:categories,name',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],
        ];
    }

    /**
     * Validation xabarlari
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Kategoriya nomi majburiy',
            'name.string' => 'Kategoriya nomi matn ko\'rinishida bo\'lishi kerak',
            'name.max' => 'Kategoriya nomi maksimal 255 ta belgi bo\'lishi kerak',
            'name.unique' => 'Bu nomdagi kategoriya allaqachon mavjud',
            'description.string' => 'Kategoriya tavsifi matn ko\'rinishida bo\'lishi kerak',
            'description.max' => 'Kategoriya tavsifi maksimal 1000 ta belgi bo\'lishi kerak',
            'parent_id.integer' => 'Ota kategoriya ID raqam bo\'lishi kerak',
            'parent_id.exists' => 'Tanlangan ota kategoriya mavjud emas',
            'is_active.boolean' => 'Faol holat true yoki false bo\'lishi kerak',
            'sort_order.integer' => 'Tartib raqami butun son bo\'lishi kerak',
            'sort_order.min' => 'Tartib raqami 0 dan kichik bo\'lishi mumkin emas',
            'sort_order.max' => 'Tartib raqami 999999 dan katta bo\'lishi mumkin emas',
        ];
    }
}
