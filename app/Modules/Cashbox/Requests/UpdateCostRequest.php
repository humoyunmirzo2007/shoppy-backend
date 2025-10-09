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
            'id' => 'required|integer|exists:money_operations,id',

            'cost_type_id' => 'nullable|integer|exists:cost_types,id',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'client_id' => 'nullable|integer|exists:clients,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'other_source_id' => 'nullable|integer|exists:other_sources,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'type' => ['required', Rule::enum(CostTypesEnum::class)],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);

        parent::prepareForValidation();
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $clientId = $this->input('client_id');
            $supplierId = $this->input('supplier_id');
            $otherSourceId = $this->input('other_source_id');
            $costTypeId = $this->input('cost_type_id');

            // Kamida bitta manba bo‘lishi kerak
            if (! $clientId && ! $supplierId && ! $otherSourceId && ! $costTypeId) {
                $validator->errors()->add('client_id', 'Kamida mijoz, ta\'minotchi , xarajat turiyoki boshqa manba tanlanishi kerak');
            }

            // Faqat bitta manba bo‘lishi kerak
            $selectedSources = array_filter([$clientId, $supplierId, $otherSourceId]);
            if (count($selectedSources) > 1) {
                $validator->errors()->add('client_id', 'Faqat bitta manba tanlanishi mumkin');
            }

            // Moslikni tekshirish
            if ($clientId && $type !== CostTypesEnum::CLIENT_PAYMENT_OUTPUT->value) {
                $validator->errors()->add('type', 'Mijoz tanlansa, type faqat CLIENT_PAYMENT_OUTPUT bo‘lishi kerak');
            }

            if ($supplierId && $type !== CostTypesEnum::SUPPLIER_PAYMENT_OUTPUT->value) {
                $validator->errors()->add('type', 'Ta\'minotchi tanlansa, type faqat SUPPLIER_PAYMENT_OUTPUT bo‘lishi kerak');
            }

            if ($otherSourceId && $type !== CostTypesEnum::OTHER_PAYMENT_OUTPUT->value) {
                $validator->errors()->add('type', 'Boshqa manba tanlansa, type faqat OTHER_PAYMENT_OUTPUT bo‘lishi kerak');
            }

            // Boshqa manba bo‘lsa cost_type_id majburiy
            if ($costTypeId && $type !== CostTypesEnum::COST_PAYMENT_OUTPUT->value) {
                $validator->errors()->add('type', 'Xarajat turi tanlansa, type faqat COST_PAYMENT_OUTPUT bo‘lishi kerak');
            }
        });
    }
}
