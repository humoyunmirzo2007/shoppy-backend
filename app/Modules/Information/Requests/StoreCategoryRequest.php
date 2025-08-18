<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class StoreCategoryRequest extends MainRequest
{
    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'max:255',
                'unique:categories',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Kategoriya nomini kiritish majburiy',
            'name.unique' => 'Bu kategoriya nomi allaqachon ro\'yxatdan o\'tgan',
            'name.max' => 'Kategoriya nomi 255 ta belgidan oshmasligi kerak'
        ];
    }
}
