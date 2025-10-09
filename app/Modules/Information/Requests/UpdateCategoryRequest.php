<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends MainRequest
{
    public function rules(): array
    {
        $categoryId = $this->route('id');

        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('categories')->ignore($categoryId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Kategoriya nomini kiritish majburiy',
            'name.unique' => 'Bu kategoriya nomi allaqachon ro\'yxatdan o\'tgan',
            'name.max' => 'Kategoriya nomi 255 ta belgidan oshmasligi kerak',
        ];
    }
}
