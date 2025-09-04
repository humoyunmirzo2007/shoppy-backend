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
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'To\'lov turi nomini kiritish majburiy',
            'name.unique' => 'Bu to\'lov turi nomi allaqachon ro\'yxatdan o\'tgan',
            'name.max' => 'To\'lov turi nomi 255 ta belgidan oshmasligi kerak',
        ];
    }
}
