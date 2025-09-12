<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;

class StoreTransferRequest extends MainRequest
{
    public function rules(): array
    {
        return [

            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'other_payment_type_id' => 'required|integer|exists:payment_types,id|different:payment_type_id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'sometimes|string|max:255',
            'date' => 'sometimes|date_format:d.m.Y',
        ];
    }
}
