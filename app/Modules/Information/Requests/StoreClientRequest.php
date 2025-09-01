<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class StoreClientRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:clients'],
            'phone_number' => ['required', 'string', 'max:12', 'unique:clients'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Mijoz nomini kiritish majburiy',
            'name.max' => 'Mijoz nomi 255 ta belgidan oshmasligi kerak',
            'name.unique' => 'Bu mijoz nomi allaqachon ro\'yxatdan o\'tgan',
            'phone_number.required' => 'Mijoz telefon raqamini kiritish majburiy',
            'phone_number.max' => 'Mijoz telefon raqami 12 ta belgidan oshmasligi kerak',
            'phone_number.unique' => 'Bu mijoz telefon raqami allaqachon ro\'yxatdan o\'tgan',
        ];
    }
}
