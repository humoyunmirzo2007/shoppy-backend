<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetProductByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ];
    }

    public function validationData()
    {
        return ['product_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Mahsulot ID si majburiy',
            'product_id.integer' => 'Mahsulot ID si son bo\'lishi kerak',
            'product_id.exists' => 'Bunday mahsulot mavjud emas',
        ];
    }
}
