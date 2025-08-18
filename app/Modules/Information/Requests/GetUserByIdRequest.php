<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetUserByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id']
        ];
    }

    public function validationData()
    {
        return ['user_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Foydalanuvchi ID si majburiy',
            'user_id.integer' => 'Foydalanuvchi ID si son bo\'lishi kerak',
            'user_id.exists' => 'Bunday foydalanuvchi mavjud emas'
        ];
    }
}
