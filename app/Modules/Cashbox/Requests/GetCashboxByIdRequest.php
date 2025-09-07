<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;

class GetCashboxByIdRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:cashboxes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Kassa ID majburiy',
            'id.integer' => 'Kassa ID raqam bo\'lishi kerak',
            'id.exists' => 'Bunday kassa mavjud emas',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);

        parent::prepareForValidation();
    }
}
