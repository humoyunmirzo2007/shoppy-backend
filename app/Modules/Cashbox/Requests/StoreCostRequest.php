<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;
use App\Modules\Cashbox\Enums\CostTypesEnum;
use Illuminate\Validation\Rule;

class StoreCostRequest extends MainRequest
{
    public function rules(): array
    {
        return [

            'cost_type_id' => 'required|integer|exists:cost_types,id',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'client_id' => 'nullable|integer|exists:clients,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'type' => ['required', Rule::enum(CostTypesEnum::class)],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $clientId = $this->input('client_id');
            $supplierId = $this->input('supplier_id');

            if ($type === CostTypesEnum::CLIENT_PAYMENT_OUTPUT->value && !$clientId) {
                $validator->errors()->add('client_id', 'Mijoz xarajati uchun mijoz tanlanishi kerak');
            }

            if ($type === CostTypesEnum::SUPPLIER_PAYMENT_OUTPUT->value && !$supplierId) {
                $validator->errors()->add('supplier_id', 'Ta\'minotchi xarajati uchun ta\'minotchi tanlanishi kerak');
            }

            if ($type === CostTypesEnum::CLIENT_PAYMENT_OUTPUT->value && $supplierId) {
                $validator->errors()->add('supplier_id', 'Mijoz xarajati uchun ta\'minotchi tanlanmasligi kerak');
            }

            if ($type === CostTypesEnum::SUPPLIER_PAYMENT_OUTPUT->value && $clientId) {
                $validator->errors()->add('client_id', 'Ta\'minotchi xarajati uchun mijoz tanlanmasligi kerak');
            }
        });
    }
}
