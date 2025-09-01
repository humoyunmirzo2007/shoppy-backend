<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetPaymentTypeByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'payment_type_id' => ['required', 'integer', 'exists:payment_types,id']
        ];
    }

    public function validationData()
    {
        return ['payment_type_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'payment_type_id.required' => 'To\'lov turi ID si majburiy',
            'payment_type_id.integer' => 'To\'lov turi ID si son bo\'lishi kerak',
            'payment_type_id.exists' => 'Bunday to\'lov turi mavjud emas'
        ];
    }
}
