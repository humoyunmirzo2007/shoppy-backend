<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetSupplierByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
        ];
    }

    public function validationData()
    {
        return ['supplier_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'Ta\'minotchi ID si majburiy',
            'supplier_id.integer' => 'Ta\'minotchi ID si son bo\'lishi kerak',
            'supplier_id.exists' => 'Bunday ta\'minotchi mavjud emas',
        ];
    }
}
