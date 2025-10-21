<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetAttributeValueByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'attribute_value_id' => ['required', 'integer', 'exists:attribute_values,id'],
        ];
    }

    public function validationData()
    {
        return ['attribute_value_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'attribute_value_id.required' => 'Atribut qiymati ID si majburiy',
            'attribute_value_id.integer' => 'Atribut qiymati ID si son bo\'lishi kerak',
            'attribute_value_id.exists' => 'Bunday atribut qiymati mavjud emas',
        ];
    }
}
