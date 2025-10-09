<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class StoreProductRequest extends MainRequest
{
    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'max:255',
                'unique:products',
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'unit' => [
                'required',
                'not_in:""',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tovar nomini kiritish majburiy',
            'name.unique' => 'Bu tovar nomi allaqachon ro\'yxatdan o\'tgan',
            'name.max' => 'Tovar nomi 255 ta belgidan oshmasligi kerak',

            'category_id.required' => 'Kategoriya tanlanmagan',
            'category_id.integer' => 'Kategoriya ID son bo\'lishi kerak',
            'category_id.exists' => 'Tanlangan kategoriya mavjud emas',

            'unit.required' => 'O\'lchov birligi majburiy',
            'unit.not_in' => 'O\'lchov birligini to\'g\'ri kiriting',
        ];
    }
}
