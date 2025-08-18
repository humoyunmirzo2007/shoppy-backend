<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends MainRequest
{
    public function rules(): array
    {

        return [
            'name' => ['required', 'string', 'max:255', 'unique:suppliers'],
            'phone_number' => ['required', 'string', 'max:12', 'unique:suppliers'],
            'address' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ta\'minotchi nomini kiritish majburiy',
            'name.max' => 'Ta\'minotchi nomi 255 ta belgidan oshmasligi kerak',
            'name.unique' => 'Bu ta\'minotchi nomi allaqachon ro\'yxatdan o\'tgan',
            'phone_number.required' => 'Ta\'minotchi telefon raqamini kiritish majburiy',
            'phone_number.max' => 'Ta\'minotchi telefon raqami 12 ta belgidan oshmasligi kerak',
            'phone_number.unique' => 'Bu ta\'minotchi telefon raqami allaqachon ro\'yxatdan o\'tgan',
            'address.required' => 'Ta\'minotchi manzilini kiritish majburiy',
            'address.max' => 'Ta\'minotchi manzili 500 ta belgidan oshmasligi kerak',
        ];
    }
}
