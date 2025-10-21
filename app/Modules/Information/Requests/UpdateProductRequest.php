<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000',
            ],
            'category_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:categories,id',
            ],
            'brand_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:brands,id',
            ],
        ];
    }

    /**
     * Validation xabarlari
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Mahsulot nomi majburiy',
            'name.string' => 'Mahsulot nomi matn ko\'rinishida bo\'lishi kerak',
            'name.max' => 'Mahsulot nomi maksimal 255 ta belgi bo\'lishi kerak',
            'description.string' => 'Mahsulot tavsifi matn ko\'rinishida bo\'lishi kerak',
            'description.max' => 'Mahsulot tavsifi maksimal 1000 ta belgi bo\'lishi kerak',
            'category_id.required' => 'Kategoriya majburiy',
            'category_id.integer' => 'Kategoriya ID si son bo\'lishi kerak',
            'category_id.exists' => 'Tanlangan kategoriya mavjud emas',
            'brand_id.required' => 'Brend majburiy',
            'brand_id.integer' => 'Brend ID si son bo\'lishi kerak',
            'brand_id.exists' => 'Tanlangan brend mavjud emas',
        ];
    }
}
