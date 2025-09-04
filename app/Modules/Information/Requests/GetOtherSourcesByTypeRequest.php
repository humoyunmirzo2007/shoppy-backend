<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetOtherSourcesByTypeRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:PRODUCT,PAYMENT'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Manba turini kiritish majburiy',
            'type.string' => 'Manba turi matn bo\'lishi kerak',
            'type.in' => 'Manba turi PRODUCT yoki PAYMENT bo\'lishi kerak',
        ];
    }
}
