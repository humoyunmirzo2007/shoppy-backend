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
                'required',
                'string',
                'max:255',
            ],
            'name_ru' => [
                'required',
                'string',
                'max:255',
            ],
            'description_uz' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'description_ru' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'brand_id' => [
                'required',
                'integer',
                'exists:brands,id',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'wholesale_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'images' => [
                'nullable',
                'array',
                'max:4',
            ],
            'images.*' => [
                'nullable',
                'file',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],
            'main_image' => [
                'nullable',
                'string',
            ],
            'attributes' => [
                'nullable',
                'array',
            ],
            'attributes.*.value_id' => [
                'required',
                'integer',
                'exists:attribute_values,id',
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
            'price.required' => 'Narx majburiy',
            'price.numeric' => 'Narx son bo\'lishi kerak',
            'price.min' => 'Narx 0 dan kichik bo\'lmasligi kerak',
            'wholesale_price.required' => 'Ulgurji narx majburiy',
            'wholesale_price.numeric' => 'Ulgurji narx son bo\'lishi kerak',
            'wholesale_price.min' => 'Ulgurji narx 0 dan kichik bo\'lmasligi kerak',
            'images.array' => 'Rasmlar massiv ko\'rinishida bo\'lishi kerak',
            'images.max' => 'Maksimal 4 ta rasm yuklash mumkin',
            'images.*.file' => 'Rasm fayl bo\'lishi kerak',
            'images.*.image' => 'Rasm fayl formati to\'g\'ri emas',
            'images.*.mimes' => 'Rasm jpeg, jpg, png yoki webp formatida bo\'lishi kerak',
            'images.*.max' => 'Rasm hajmi maksimal 2MB bo\'lishi kerak',
            'attributes.array' => 'Atributlar massiv ko\'rinishida bo\'lishi kerak',
            'attributes.*.value_id.required' => 'Atribut qiymati ID si majburiy',
            'attributes.*.value_id.integer' => 'Atribut qiymati ID si son bo\'lishi kerak',
            'attributes.*.value_id.exists' => 'Tanlangan atribut qiymati mavjud emas',
        ];
    }
}
