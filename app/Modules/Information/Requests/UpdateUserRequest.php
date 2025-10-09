<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends MainRequest
{
    public function rules(): array
    {
        $position = $this->input('position');
        $userId = $this->route('id');

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'username' => [
                Rule::requiredIf($position !== 'MANAGER'),
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'phone_number' => [
                'required',
                'string',
                'max:12',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:255',
                'not_in:""',
            ],
            'position' => ['required'],
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
            'password.min' => 'Foydalanuvchi paroli kamida 8 ta belgidan iborat bo\'lishi kerak',
            'password.max' => 'Foydalanuvchi paroli 255 ta belgidan oshmasligi kerak',
            'position.required' => 'Foydalanuvchi lavozimini tanlash majburiy',
            'password.not_in' => 'Foydalanuvchi paroli bo\'sh bo\'lishi mumkin emas',
        ];
    }
}
