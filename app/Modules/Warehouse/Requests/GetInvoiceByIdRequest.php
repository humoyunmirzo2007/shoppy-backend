<?php

namespace App\Modules\Warehouse\Requests;

use App\Http\Requests\MainRequest;

class GetInvoiceByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'],
        ];
    }

    public function validationData()
    {
        return ['invoice_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'invoice_id.required' => 'Faktura ID si majburiy',
            'invoice_id.integer' => 'Faktura ID si son bo\'lishi kerak',
            'invoice_id.exists' => 'Bunday faktura mavjud emas',
        ];
    }
}
