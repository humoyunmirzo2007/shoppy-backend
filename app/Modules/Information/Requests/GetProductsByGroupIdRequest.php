<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetProductsByGroupIdRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'product_group_id' => ['required', 'integer', 'exists:product_groups,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_group_id.required' => 'Mahsulot guruhi ID si kiritilishi shart',
            'product_group_id.integer' => 'Mahsulot guruhi ID si son bo\'lishi kerak',
            'product_group_id.exists' => 'Tanlangan mahsulot guruhi mavjud emas',
        ];
    }
}
