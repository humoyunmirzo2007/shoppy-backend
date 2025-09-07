<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;

class StoreCashboxRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'payment_type_id' => [
                'required',
                'integer',
                'exists:payment_types,id',
                'unique:cashboxes,payment_type_id,NULL,id,user_id,' . $this->user_id
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Kassa nomi majburiy',
            'name.string' => 'Kassa nomi matn bo\'lishi kerak',
            'name.max' => 'Kassa nomi 255 belgidan oshmasligi kerak',
            'user_id.required' => 'Foydalanuvchi majburiy',
            'user_id.integer' => 'Foydalanuvchi ID raqam bo\'lishi kerak',
            'user_id.exists' => 'Bunday foydalanuvchi mavjud emas',
            'payment_type_id.required' => 'To\'lov turi majburiy',
            'payment_type_id.integer' => 'To\'lov turi ID raqam bo\'lishi kerak',
            'payment_type_id.exists' => 'Bunday to\'lov turi mavjud emas',
            'payment_type_id.unique' => 'Bu foydalanuvchi uchun ushbu to\'lov turi bilan kassa allaqachon mavjud',
        ];
    }
}
