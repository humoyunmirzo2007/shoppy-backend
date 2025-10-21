<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetProductVariantByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
        ];
    }

    public function validationData()
    {
        return ['product_variant_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'product_variant_id.required' => 'Mahsulot varianti ID si majburiy',
            'product_variant_id.integer' => 'Mahsulot varianti ID si son bo\'lishi kerak',
            'product_variant_id.exists' => 'Bunday mahsulot varianti mavjud emas',
        ];
    }
}
