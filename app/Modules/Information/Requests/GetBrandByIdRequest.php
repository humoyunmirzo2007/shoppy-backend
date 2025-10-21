<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetBrandByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
        ];
    }

    public function validationData()
    {
        return ['brand_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'brand_id.required' => 'Brend ID si majburiy',
            'brand_id.integer' => 'Brend ID si son bo\'lishi kerak',
            'brand_id.exists' => 'Bunday brend mavjud emas',
        ];
    }
}
