<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class UpdateUserPasswordRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'string'],
            'new_password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Joriy parolni kiritish majburiy',
            'password.string' => 'Joriy parol matn bo\'lishi kerak',
            'password.min' => 'Joriy parol kamida 8 belgidan iborat bo\'lishi kerak',
            'new_password.required' => 'Yangi parolni kiritish majburiy',
            'new_password.string' => 'Yangi parol matn bo\'lishi kerak',
            'new_password.min' => 'Yangi parol kamida 8 belgidan iborat bo\'lishi kerak',
        ];
    }
}
