<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class StoreOtherSourceRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:other_sources',
            ],
            'type' => [
                'required',
                'string',
                'in:INVOICE,PAYMENT'
            ],
            'is_active' => [
                'nullable',
                'boolean'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Manba nomini kiritish majburiy',
            'name.string' => 'Manba nomi matn bo\'lishi kerak',
            'name.max' => 'Manba nomi 255 ta belgidan oshmasligi kerak',
            'name.unique' => 'Bu manba nomi allaqachon ro\'yxatdan o\'tgan',

            'type.required' => 'Manba turini tanlash majburiy',
            'type.string' => 'Manba turi matn bo\'lishi kerak',
            'type.in' => 'Manba turi INVOICE yoki PAYMENT bo\'lishi kerak',

            'is_active.boolean' => 'Faol holat mantiqiy qiymat bo\'lishi kerak',
        ];
    }
}
