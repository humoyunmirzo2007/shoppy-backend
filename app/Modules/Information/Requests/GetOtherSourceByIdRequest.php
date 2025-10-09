<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetOtherSourceByIdRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:other_sources,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'ID kiritish majburiy',
            'id.integer' => 'ID son bo\'lishi kerak',
            'id.exists' => 'Bunday manba mavjud emas',
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
