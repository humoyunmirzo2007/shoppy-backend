<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class StorePaymentTypeRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                'unique:payment_types',
            ],
            'residue' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'To\'lov turi nomini kiritish majburiy',
            'name.unique' => 'Bu to\'lov turi nomi allaqachon ro\'yxatdan o\'tgan',
            'name.max' => 'To\'lov turi nomi 255 ta belgidan oshmasligi kerak',
            'residue.required' => 'Qoldiq miqdorini kiritish majburiy',
            'residue.numeric' => 'Qoldiq miqdori raqam bo\'lishi kerak',
            'residue.min' => 'Qoldiq miqdori manfiy bo\'lmasligi kerak',
        ];
    }
}
