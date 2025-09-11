<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;

class GetTransfersRequest extends MainRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'limit' => 'sometimes|integer|min:1|max:100',
            'payment_type_id' => 'sometimes|integer|exists:payment_types,id',
            'other_payment_type_id' => 'sometimes|integer|exists:payment_types,id',
            'date_from' => 'sometimes|date_format:d.m.Y',
            'date_to' => 'sometimes|date_format:d.m.Y',
            'sort' => 'sometimes|array',
            'sort.*.field' => 'sometimes|string|in:id,amount,created_at',
            'sort.*.direction' => 'sometimes|string|in:asc,desc',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'payment_type_id.exists' => 'Tanlangan to\'lov turi mavjud emas',
            'other_payment_type_id.exists' => 'Tanlangan boshqa to\'lov turi mavjud emas',
            'date_from.date_format' => 'Sana formati noto\'g\'ri (dd.mm.yyyy)',
            'date_to.date_format' => 'Sana formati noto\'g\'ri (dd.mm.yyyy)',
        ];
    }
}
