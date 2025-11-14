<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreToExistingProductGroupRequest extends FormRequest
{
    /**
     * Request ni tasdiqlash huquqini tekshirish
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation xatolarini qayta ishlash
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        // Fayl yuklash xatolarini olib tashlash
        $filteredErrors = [];
        foreach ($errors->messages() as $key => $messages) {
            // Agar "failed to upload" xatosi bo'lsa, uni o'tkazib yuborish
            $hasUploadError = false;
            foreach ($messages as $message) {
                if (stripos($message, 'failed to upload') !== false) {
                    $hasUploadError = true;
                    break;
                }
            }

            if (! $hasUploadError) {
                $filteredErrors[$key] = $messages;
            }
        }

        // Agar boshqa xatolar bo'lsa, ularni qaytarish
        if (! empty($filteredErrors)) {
            // Xatoliklarni tozalash va qayta qo'shish
            foreach ($errors->keys() as $key) {
                $errors->forget($key);
            }
            foreach ($filteredErrors as $key => $messages) {
                foreach ($messages as $message) {
                    $errors->add($key, $message);
                }
            }
            parent::failedValidation($validator);
        }

        // Agar faqat fayl yuklash xatolari bo'lsa, validation o'tkazish
        // (chunki rasmlar ixtiyoriy)
    }

    /**
     * Validation qoidalari
     */
    public function rules(): array
    {
        return [
            'product_group_id' => [
                'required',
                'integer',
                'exists:product_groups,id',
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
            'products' => [
                'required',
                'array',
                'min:1',
            ],
            'products.*.name_uz' => [
                'required',
                'string',
            ],
            'products.*.name_ru' => [
                'required',
                'string',
            ],
            'products.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'products.*.wholesale_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'products.*.images' => [
                'nullable',
                'array',
                'max:4',
            ],
            'products.*.images.*' => [
                'nullable',
                'sometimes',
                'file',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
            ],
            'products.*.main_image' => [
                'nullable',
                'string',
            ],
            'products.*.description_uz' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'products.*.description_ru' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'products.*.attributes' => [
                'required',
                'array',
                'min:1',
            ],
            'products.*.attributes.*.attribute_id' => [
                'required',
                'integer',
                'exists:attributes,id',
            ],
            'products.*.attributes.*.value_id' => [
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
            'product_group_id.required' => 'Mahsulot guruhi ID majburiy',
            'product_group_id.integer' => 'Mahsulot guruhi ID son bo\'lishi kerak',
            'product_group_id.exists' => 'Tanlangan mahsulot guruhi mavjud emas',
            'category_id.required' => 'Kategoriya majburiy',
            'category_id.integer' => 'Kategoriya ID si son bo\'lishi kerak',
            'category_id.exists' => 'Tanlangan kategoriya mavjud emas',
            'brand_id.required' => 'Brend majburiy',
            'brand_id.integer' => 'Brend ID si son bo\'lishi kerak',
            'brand_id.exists' => 'Tanlangan brend mavjud emas',
            'products.required' => 'Mahsulot variantlari majburiy',
            'products.array' => 'Mahsulot variantlari massiv ko\'rinishida bo\'lishi kerak',
            'products.min' => 'Kamida bitta mahsulot varianti bo\'lishi kerak',
            'products.*.name_uz.required' => 'Mahsulot varianti nomi (o\'zbek) majburiy',
            'products.*.name_uz.string' => 'Mahsulot varianti nomi (o\'zbek) matn ko\'rinishida bo\'lishi kerak',
            'products.*.name_ru.required' => 'Mahsulot varianti nomi (rus) majburiy',
            'products.*.name_ru.string' => 'Mahsulot varianti nomi (rus) matn ko\'rinishida bo\'lishi kerak',
            'products.*.price.required' => 'Narx majburiy',
            'products.*.price.numeric' => 'Narx raqam bo\'lishi kerak',
            'products.*.price.min' => 'Narx 0 yoki undan katta bo\'lishi kerak',
            'products.*.wholesale_price.required' => 'Ulgurji narx majburiy',
            'products.*.wholesale_price.numeric' => 'Ulgurji narx raqam bo\'lishi kerak',
            'products.*.wholesale_price.min' => 'Ulgurji narx 0 yoki undan katta bo\'lishi kerak',
            'products.*.images.array' => 'Rasmlar massiv ko\'rinishida bo\'lishi kerak',
            'products.*.images.max' => 'Maksimal 4 ta rasm yuklash mumkin',
            'products.*.images.*.file' => 'Rasm fayl bo\'lishi kerak',
            'products.*.images.*.image' => 'Rasm fayl bo\'lishi kerak',
            'products.*.images.*.mimes' => 'Rasm faqat jpeg, jpg, png yoki webp formatida bo\'lishi kerak',
            'products.*.images.*.max' => 'Rasm hajmi maksimal 2MB bo\'lishi kerak',
            'products.*.main_image.string' => 'Asosiy rasm matn ko\'rinishida bo\'lishi kerak',
            'products.*.description_uz.string' => 'Tavsif (o\'zbek) matn ko\'rinishida bo\'lishi kerak',
            'products.*.description_uz.max' => 'Tavsif (o\'zbek) maksimal 1000 ta belgi bo\'lishi kerak',
            'products.*.description_ru.string' => 'Tavsif (rus) matn ko\'rinishida bo\'lishi kerak',
            'products.*.description_ru.max' => 'Tavsif (rus) maksimal 1000 ta belgi bo\'lishi kerak',
            'products.*.attributes.required' => 'Atributlar majburiy',
            'products.*.attributes.array' => 'Atributlar massiv ko\'rinishida bo\'lishi kerak',
            'products.*.attributes.min' => 'Kamida bitta atribut bo\'lishi kerak',
            'products.*.attributes.*.attribute_id.required' => 'Atribut ID majburiy',
            'products.*.attributes.*.attribute_id.integer' => 'Atribut ID son bo\'lishi kerak',
            'products.*.attributes.*.attribute_id.exists' => 'Tanlangan atribut mavjud emas',
            'products.*.attributes.*.value_id.required' => 'Atribut qiymati ID majburiy',
            'products.*.attributes.*.value_id.integer' => 'Atribut qiymati ID son bo\'lishi kerak',
            'products.*.attributes.*.value_id.exists' => 'Tanlangan atribut qiymati mavjud emas',
        ];
    }
}
