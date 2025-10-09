<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetCostTypeByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'cost_type_id' => ['required', 'integer', 'exists:cost_types,id'],
        ];
    }

    public function validationData()
    {
        return ['cost_type_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'cost_type_id.required' => 'Xarajat turi ID si majburiy',
            'cost_type_id.integer' => 'Xarajat turi ID si son bo\'lishi kerak',
            'cost_type_id.exists' => 'Bunday xarajat turi mavjud emas',
        ];
    }
}
