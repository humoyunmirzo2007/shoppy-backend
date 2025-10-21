<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetAttributeByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'attribute_id' => ['required', 'integer', 'exists:attributes,id'],
        ];
    }

    public function validationData()
    {
        return ['attribute_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'attribute_id.required' => 'Atribut ID si majburiy',
            'attribute_id.integer' => 'Atribut ID si son bo\'lishi kerak',
            'attribute_id.exists' => 'Bunday atribut mavjud emas',
        ];
    }
}
