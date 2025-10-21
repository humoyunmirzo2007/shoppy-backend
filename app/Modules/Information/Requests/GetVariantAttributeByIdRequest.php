<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetVariantAttributeByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'variant_attribute_id' => ['required', 'integer', 'exists:variant_attributes,id'],
        ];
    }

    public function validationData()
    {
        return ['variant_attribute_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'variant_attribute_id.required' => 'Variant atributi ID si majburiy',
            'variant_attribute_id.integer' => 'Variant atributi ID si son bo\'lishi kerak',
            'variant_attribute_id.exists' => 'Bunday variant atributi mavjud emas',
        ];
    }
}
