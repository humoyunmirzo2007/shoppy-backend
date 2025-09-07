<?php

namespace App\Modules\Cashbox\Requests;

use App\Http\Requests\MainRequest;
use App\Modules\Cashbox\Enums\CostTypesEnum;
use Illuminate\Validation\Rule;

class UpdateCostRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:costs,id',
            'user_id' => 'sometimes|integer|exists:users,id',
            'cost_type_id' => 'sometimes|integer|exists:cost_types,id',
            'client_id' => 'nullable|integer|exists:clients,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'amount' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'sometimes|string|max:50',
            'type' => ['sometimes', Rule::enum(CostTypesEnum::class)],
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

            if ($type && $type === CostTypesEnum::CLIENT_COST->value && !$clientId) {
                $validator->errors()->add('client_id', 'Mijoz xarajati uchun mijoz tanlanishi kerak');
            }

            if ($type && $type === CostTypesEnum::SUPPLIER_COST->value && !$supplierId) {
                $validator->errors()->add('supplier_id', 'Ta\'minotchi xarajati uchun ta\'minotchi tanlanishi kerak');
            }

            if ($type && $type === CostTypesEnum::CLIENT_COST->value && $supplierId) {
                $validator->errors()->add('supplier_id', 'Mijoz xarajati uchun ta\'minotchi tanlanmasligi kerak');
            }

            if ($type && $type === CostTypesEnum::SUPPLIER_COST->value && $clientId) {
                $validator->errors()->add('client_id', 'Ta\'minotchi xarajati uchun mijoz tanlanmasligi kerak');
            }
        });
    }
}
