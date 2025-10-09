<?php

namespace App\Http\Requests;

use App\Helpers\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MainRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errors = collect($validator->errors())->flatten()->toArray();

        throw new HttpResponseException(Response::error($errors, 'Ma\'lumotlar to\'liq emas', 422));
    }

    public function prepareForValidation()
    {
        $allowedFields = array_keys($this->rules());
        $extraFields = array_diff(array_keys($this->all()), $allowedFields);
        $extraFields = array_values($extraFields);
        if (count($extraFields) > 0) {
            throw new HttpResponseException(Response::error($extraFields, 'Ortiqcha ma\'lumotlar kiritilgan', 422));
        }
    }
}
