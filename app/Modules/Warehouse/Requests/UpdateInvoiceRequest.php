<?php

namespace App\Modules\Warehouse\Requests;

use App\Http\Requests\MainRequest;

class UpdateInvoiceRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'numeric', 'exists:suppliers,id'],
            'commentary' => ['nullable', 'max:200'],
            'type' => ['required', 'in:SUPPLIER_INPUT,SUPPLIER_OUTPUT'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.count' => ['required', 'numeric', 'gt:0'],
            'products.*.price' => ['required', 'numeric', 'gte:0'],
            'products.*.action' => ['required', 'string', 'in:normal,add,edit,delete'],
            'products.*.id' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Ta\'minotchi tanlanmagan',
            'supplier_id.numeric' => 'Ta\'minotchi ID noto\'g\'ri formatda',
            'supplier_id.exists' => 'Bunday Ta\'minotchi mavjud emas',
            'commentary.max' => 'Izoh 200 ta belgidan oshmasligi kerak',
            'type.required' => 'Faktura turini tanlash shart',
            'type.in' => 'Faktura turini noto\'g\'ri',
            'products.required' => 'Mahsulotlar ro\'yxati bo\'sh bo\'lishi mumkin emas',
            'products.array' => 'Mahsulotlar noto\'g\'ri formatda yuborilgan',
            'products.min' => 'Kamida bitta mahsulot qo\'shilishi kerak',
            'products.*.product_id.required' => 'Mahsulot tanlanmagan',
            'products.*.product_id.integer' => 'Mahsulot ID raqam bo\'lishi kerak',
            'products.*.product_id.exists' => 'Tanlangan mahsulot topilmadi',
            'products.*.count.required' => 'Mahsulot miqdori kiritilishi kerak',
            'products.*.count.numeric' => 'Mahsulot miqdori raqam bo\'lishi kerak',
            'products.*.count.gt' => 'Mahsulot miqdori 0 dan katta bo\'lishi kerak',
            'products.*.price.required' => 'Mahsulot narxi kiritilishi kerak',
            'products.*.price.numeric' => 'Mahsulot narxi raqam bo\'lishi kerak',
            'products.*.price.gte' => 'Mahsulot narxi musbat bo\'lishi kerak',
            'products.*.action.required' => 'Mahsulot actioni  bo\'lishi shart',
            'products.*.action.string' => 'Mahsulot actioni string bo\'lishi kerak',
            'products.*.action.in' => 'Mahsulot actioni faqat normal, add, edit yoki delete bo\'lishi kerak',
            'products.*.id.required' => 'Faktura mahsulot idsi bo\'lishi shart',
        ];
    }
}
