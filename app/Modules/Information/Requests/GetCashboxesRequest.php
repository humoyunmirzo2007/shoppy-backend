<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class GetCashboxesRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'is_active' => 'sometimes|boolean',
            'user_id' => 'sometimes|integer|exists:users,id',
            'payment_type_id' => 'sometimes|integer|exists:payment_types,id',
            'name' => 'sometimes|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'is_active.boolean' => 'Faollik holati mantiqiy qiymat bo\'lishi kerak',
            'user_id.integer' => 'Foydalanuvchi ID raqam bo\'lishi kerak',
            'user_id.exists' => 'Bunday foydalanuvchi mavjud emas',
            'payment_type_id.integer' => 'To\'lov turi ID raqam bo\'lishi kerak',
            'payment_type_id.exists' => 'Bunday to\'lov turi mavjud emas',
            'name.string' => 'Nom matn bo\'lishi kerak',
            'name.max' => 'Nom 255 belgidan oshmasligi kerak',
        ];
    }
}
