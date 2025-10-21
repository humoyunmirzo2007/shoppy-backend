<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
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
                'unique:brands,name',
            ],
        ];
    }

    /**
     * Validation xabarlari
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Brend nomi majburiy',
            'name.string' => 'Brend nomi matn ko\'rinishida bo\'lishi kerak',
            'name.max' => 'Brend nomi maksimal 255 ta belgi bo\'lishi kerak',
            'name.unique' => 'Bu nomdagi brend allaqachon mavjud',
        ];
    }
}
