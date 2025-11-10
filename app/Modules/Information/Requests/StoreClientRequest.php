<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class StoreClientRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:12', 'unique:clients'],
            'chat_id' => ['required', 'string', 'max:255', 'unique:clients'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Mijoz ismini kiritish majburiy',
            'first_name.max' => 'Mijoz ismi 255 ta belgidan oshmasligi kerak',
            'middle_name.max' => 'Mijoz otasining ismi 255 ta belgidan oshmasligi kerak',
            'last_name.max' => 'Mijoz familiyasi 255 ta belgidan oshmasligi kerak',
            'phone_number.required' => 'Mijoz telefon raqamini kiritish majburiy',
            'phone_number.max' => 'Mijoz telefon raqami 12 ta belgidan oshmasligi kerak',
            'phone_number.unique' => 'Bu mijoz telefon raqami allaqachon ro\'yxatdan o\'tgan',
            'chat_id.required' => 'Mijoz chat ID sini kiritish majburiy',
            'chat_id.max' => 'Mijoz chat ID si 255 ta belgidan oshmasligi kerak',
            'chat_id.unique' => 'Bu mijoz chat ID si allaqachon ro\'yxatdan o\'tgan',
        ];
    }
}
