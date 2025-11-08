<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('product_groups', 'name')->ignore($id)],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Mahsulot guruhi nomi kiritilishi shart',
            'name.string' => 'Mahsulot guruhi nomi matn ko\'rinishida bo\'lishi kerak',
            'name.max' => 'Mahsulot guruhi nomi maksimal 255 belgidan oshmasligi kerak',
            'name.unique' => 'Bu mahsulot guruhi nomi allaqachon mavjud',
            'brand_id.required' => 'Brend tanlanishi shart',
            'brand_id.integer' => 'Brend ID raqam bo\'lishi kerak',
            'brand_id.exists' => 'Tanlangan brend mavjud emas',
        ];
    }
}
