<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends MainRequest
{
    public function rules(): array
    {
        $position = $this->input('position');

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'username' => [
                Rule::requiredIf($position !== 'MANAGER'),
                'string',
                'max:255',
                'unique:users',
            ],
            'phone_number' => ['required', 'string', 'max:12', 'unique:users'],
            'password' => [
                Rule::requiredIf($position !== 'MANAGER'),
                'string',
                'min:8',
                'max:255'
            ],
            'position' => ['required', Rule::in(['ADMIN', 'MANAGER', 'TRADE_MANAGER'])]
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Foydalanuvchi to\'liq FIOsini kiritish majburiy',
            'full_name.max' => 'Foydalanuvchi to\'liq FIOsi 255 ta belgidan oshmasligi kerak',
            'username.required' => 'Foydalanuvchi nomini kiritish majburiy',
            'username.max' => 'Foydalanuvchi nomi 255 ta belgidan oshmasligi kerak',
            'username.unique' => 'Bu foydalanuvchi nomi allaqachon ro\'yxatdan o\'tgan',
            'phone_number.required' => 'Foydalanuvchi telefon raqamini kiritish majburiy',
            'phone_number.max' => 'Foydalanuvchi telefon raqami 12 ta belgidan oshmasligi kerak',
            'phone_number.unique' => 'Bu foydalanuvchi telefon raqami allaqachon ro\'yxatdan o\'tgan',
            'password.required' => 'Foydalanuvchi parolini kiritish majburiy',
            'password.min' => 'Foydalanuvchi paroli kamida 8 ta belgidan iborat bo\'lishi kerak',
            'password.max' => 'Foydalanuvchi paroli 255 ta belgidan oshmasligi kerak',
            'position.required' => 'Foydalanuvchi lavozimini tanlash majburiy',
            'position.in' => 'Foydalanuvchi lavozimi faqat ADMIN MANAGER yoki TRADE_MANAGER bo\'lishi mumkin',
        ];
    }
}
