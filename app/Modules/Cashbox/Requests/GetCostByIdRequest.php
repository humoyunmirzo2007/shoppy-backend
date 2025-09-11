<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;

class GetCostByIdRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:money_operations,id',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id')
        ]);

        parent::prepareForValidation();
    }
}
