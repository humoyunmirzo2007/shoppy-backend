<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class TranslateRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:5000'],
            'source' => ['nullable', 'string', 'size:2'],
            'target' => ['nullable', 'string', 'size:2'],
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Tarjima qilinadigan matn kiritilishi shart',
            'text.string' => 'Matn matn ko\'rinishida bo\'lishi kerak',
            'text.max' => 'Matn maksimal 5000 belgidan oshmasligi kerak',
            'source.string' => 'Manba tili matn ko\'rinishida bo\'lishi kerak',
            'source.size' => 'Manba tili 2 belgidan iborat bo\'lishi kerak',
            'target.string' => 'Maqsad tili matn ko\'rinishida bo\'lishi kerak',
            'target.size' => 'Maqsad tili 2 belgidan iborat bo\'lishi kerak',
        ];
    }
}
