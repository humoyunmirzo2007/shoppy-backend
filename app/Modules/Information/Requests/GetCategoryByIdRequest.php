<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetCategoryByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function validationData()
    {
        return ['category_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Kategoriya ID si majburiy',
            'category_id.integer' => 'Kategoriya ID si son bo\'lishi kerak',
            'category_id.exists' => 'Bunday kategoriya mavjud emas',
        ];
    }
}
