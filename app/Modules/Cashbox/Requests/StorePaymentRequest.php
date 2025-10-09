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
            'other_source_id' => 'nullable|integer|exists:other_sources,id',
            'cost_type_id' => 'nullable|integer|exists:cost_types,id',
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
            $otherSourceId = $this->input('other_source_id');
            $costTypeId = $this->input('cost_type_id');

            if (! $clientId && ! $supplierId && ! $otherSourceId && ! $costTypeId) {
                $validator->errors()->add('client_id', 'Kamida mijoz, ta\'minotchi , xarajat turiyoki boshqa manba tanlanishi kerak');
            }

            if ($type === PaymentTypesEnum::CLIENT_PAYMENT_INPUT->value && ! $clientId) {
                $validator->errors()->add('client_id', 'Mijoz to\'lovi uchun mijoz tanlanishi kerak');
            }

            if ($type === PaymentTypesEnum::SUPPLIER_PAYMENT_INPUT->value && ! $supplierId) {
                $validator->errors()->add('supplier_id', 'Ta\'minotchi to\'lovi uchun ta\'minotchi tanlanishi kerak');
            }

            if ($type === PaymentTypesEnum::CLIENT_PAYMENT_INPUT->value && $supplierId) {
                $validator->errors()->add('supplier_id', 'Mijoz to\'lovi uchun ta\'minotchi tanlanmasligi kerak');
            }

            if ($type === PaymentTypesEnum::SUPPLIER_PAYMENT_INPUT->value && $clientId) {
                $validator->errors()->add('client_id', 'Ta\'minotchi to\'lovi uchun mijoz tanlanmasligi kerak');
            }

            if ($type === PaymentTypesEnum::OTHER_PAYMENT_INPUT->value && ! $otherSourceId) {
                $validator->errors()->add('other_source_id', 'Boshqa manba to\'lovi uchun boshqa manba tanlanishi kerak');
            }

            if ($type === PaymentTypesEnum::COST_PAYMENT_INPUT->value && ! $costTypeId) {
                $validator->errors()->add('cost_type_id', 'Xarajat to\'lovi uchun xarajat turi tanlanishi kerak');
            }
        });
    }
}
