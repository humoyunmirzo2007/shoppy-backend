<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetClientByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id']
        ];
    }

    public function validationData()
    {
        return ['client_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'client_id.required' => 'Mijoz ID si majburiy',
            'client_id.integer' => 'Mijoz ID si son bo\'lishi kerak',
            'client_id.exists' => 'Bunday mijoz mavjud emas'
        ];
    }
}
