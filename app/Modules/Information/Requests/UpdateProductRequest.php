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
            'name_uz' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'name_ru' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'description_uz' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000',
            ],
            'description_ru' => [
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
            'name_uz.required' => 'Mahsulot nomi (o\'zbek) majburiy',
            'name_uz.string' => 'Mahsulot nomi (o\'zbek) matn ko\'rinishida bo\'lishi kerak',
            'name_uz.max' => 'Mahsulot nomi (o\'zbek) maksimal 255 ta belgi bo\'lishi kerak',
            'name_ru.required' => 'Mahsulot nomi (rus) majburiy',
            'name_ru.string' => 'Mahsulot nomi (rus) matn ko\'rinishida bo\'lishi kerak',
            'name_ru.max' => 'Mahsulot nomi (rus) maksimal 255 ta belgi bo\'lishi kerak',
            'description_uz.string' => 'Mahsulot tavsifi (o\'zbek) matn ko\'rinishida bo\'lishi kerak',
            'description_uz.max' => 'Mahsulot tavsifi (o\'zbek) maksimal 1000 ta belgi bo\'lishi kerak',
            'description_ru.string' => 'Mahsulot tavsifi (rus) matn ko\'rinishida bo\'lishi kerak',
            'description_ru.max' => 'Mahsulot tavsifi (rus) maksimal 1000 ta belgi bo\'lishi kerak',
            'category_id.required' => 'Kategoriya majburiy',
            'category_id.integer' => 'Kategoriya ID si son bo\'lishi kerak',
            'category_id.exists' => 'Tanlangan kategoriya mavjud emas',
            'brand_id.required' => 'Brend majburiy',
            'brand_id.integer' => 'Brend ID si son bo\'lishi kerak',
            'brand_id.exists' => 'Tanlangan brend mavjud emas',
        ];
    }
}
