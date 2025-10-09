<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class StoreCostTypeRequest extends MainRequest
{
    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'max:255',
                'unique:cost_types',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Xarajat turi nomini kiritish majburiy',
            'name.unique' => 'Bu xarajat turi nomi allaqachon ro\'yxatdan o\'tgan',
            'name.max' => 'Xarajat turi nomi 255 ta belgidan oshmasligi kerak',
        ];
    }
}
