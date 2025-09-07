<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;
use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:payments,id',
            'user_id' => 'sometimes|integer|exists:users,id',
            'payment_type_id' => 'sometimes|integer|exists:payment_types,id',
            'client_id' => 'nullable|integer|exists:clients,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'amount' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'sometimes|string|max:50',
            'type' => ['sometimes', Rule::enum(PaymentTypesEnum::class)],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id')
        ]);

        parent::prepareForValidation();
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $clientId = $this->input('client_id');
            $supplierId = $this->input('supplier_id');

            if ($type && $type === PaymentTypesEnum::CLIENT_PAYMENT->value && !$clientId) {
                $validator->errors()->add('client_id', 'Mijoz to\'lovi uchun mijoz tanlanishi kerak');
            }

            if ($type && $type === PaymentTypesEnum::SUPPLIER_PAYMENT->value && !$supplierId) {
                $validator->errors()->add('supplier_id', 'Ta\'minotchi to\'lovi uchun ta\'minotchi tanlanishi kerak');
            }

            if ($type && $type === PaymentTypesEnum::CLIENT_PAYMENT->value && $supplierId) {
                $validator->errors()->add('supplier_id', 'Mijoz to\'lovi uchun ta\'minotchi tanlanmasligi kerak');
            }

            if ($type && $type === PaymentTypesEnum::SUPPLIER_PAYMENT->value && $clientId) {
                $validator->errors()->add('client_id', 'Ta\'minotchi to\'lovi uchun mijoz tanlanmasligi kerak');
            }
        });
    }
}
