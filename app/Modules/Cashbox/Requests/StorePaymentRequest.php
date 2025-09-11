<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;
use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'client_id' => 'nullable|integer|exists:clients,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'type' => ['required', Rule::enum(PaymentTypesEnum::class)],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $clientId = $this->input('client_id');
            $supplierId = $this->input('supplier_id');

            if ($type === PaymentTypesEnum::CLIENT_PAYMET_INPUTS->value && !$clientId) {
                $validator->errors()->add('client_id', 'Mijoz to\'lovi uchun mijoz tanlanishi kerak');
            }

            if ($type === PaymentTypesEnum::SUPPLIER_PAYMET_INPUTS->value && !$supplierId) {
                $validator->errors()->add('supplier_id', 'Ta\'minotchi to\'lovi uchun ta\'minotchi tanlanishi kerak');
            }

            if ($type === PaymentTypesEnum::CLIENT_PAYMET_INPUTS->value && $supplierId) {
                $validator->errors()->add('supplier_id', 'Mijoz to\'lovi uchun ta\'minotchi tanlanmasligi kerak');
            }

            if ($type === PaymentTypesEnum::SUPPLIER_PAYMET_INPUTS->value && $clientId) {
                $validator->errors()->add('client_id', 'Ta\'minotchi to\'lovi uchun mijoz tanlanmasligi kerak');
            }
        });
    }
}
