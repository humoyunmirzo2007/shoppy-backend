<?php

namespace App\Http\Requests;

class LoginRequest extends MainRequest
{
    public function rules()
    {
        $rules = [
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:255'],
        ];

        if (! config('captcha.disable')) {
            $rules['key'] = ['required', 'string'];
            $rules['captcha'] = [
                'required',
                'captcha_api:'.request('key').',math',
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'username.required' => 'Foydalanuvchi nomi kiritilishi shart',
            'password.required' => 'Parol kiritilishi shart',
            'captcha.required' => 'Captcha kiritilishi shart',
            'captcha.captcha_api' => 'Captcha noto\'g\'ri',
            'key.required' => 'Captcha kaliti kiritilishi shart',
        ];
    }
}
