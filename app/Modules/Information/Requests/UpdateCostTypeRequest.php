<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;
use Illuminate\Validation\Rule;

class UpdateCostTypeRequest extends MainRequest
{
    public function rules(): array
    {
        $costTypeId = $this->route('id');

        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('cost_types')->ignore($costTypeId),
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
