<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductGroupByIdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
