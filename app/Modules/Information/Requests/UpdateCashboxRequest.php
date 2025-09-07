<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class UpdateCashboxRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:cashboxes,id',
            'name' => 'sometimes|string|max:255',
            'user_id' => 'sometimes|integer|exists:users,id',
            'payment_type_id' => 'sometimes|integer|exists:payment_types,id',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Kassa ID majburiy',
            'id.integer' => 'Kassa ID raqam bo\'lishi kerak',
            'id.exists' => 'Bunday kassa mavjud emas',
            'name.string' => 'Kassa nomi matn bo\'lishi kerak',
            'name.max' => 'Kassa nomi 255 belgidan oshmasligi kerak',
            'user_id.integer' => 'Foydalanuvchi ID raqam bo\'lishi kerak',
            'user_id.exists' => 'Bunday foydalanuvchi mavjud emas',
            'payment_type_id.integer' => 'To\'lov turi ID raqam bo\'lishi kerak',
            'payment_type_id.exists' => 'Bunday to\'lov turi mavjud emas',
            'is_active.boolean' => 'Faollik holati mantiqiy qiymat bo\'lishi kerak',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);

        parent::prepareForValidation();
    }
}
