<?php

namespace App\Modules\Warehouse\Requests;

use App\Http\Requests\MainRequest;

class UpdateInvoiceRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'supplier_id' => ['nullable', 'numeric', 'exists:suppliers,id'],
            'other_source_id' => ['nullable', 'numeric', 'exists:other_sources,id'],
            'commentary' => ['nullable', 'max:200'],
            'type' => ['required', 'in:SUPPLIER_INPUT,SUPPLIER_OUTPUT,OTHER_INPUT,OTHER_OUTPUT'],
            'date' => ['required', 'date_format:d.m.Y'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.count' => ['required', 'numeric', 'gt:0'],
            'products.*.price' => ['required', 'numeric', 'gte:0'],
            'products.*.action' => ['required', 'string', 'in:normal,add,edit,delete'],
            'products.*.id' => ['nullable', 'numeric'],
            'products.*.input_price' => ['required', 'numeric', 'gte:0'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $supplierId = $this->input('supplier_id');
            $otherSourceId = $this->input('other_source_id');
            $type = $this->input('type');
            $products = $this->input('products', []);

            if (!$supplierId && !$otherSourceId) {
                $validator->errors()->add('supplier_id', 'Ta\'minotchi yoki boshqa manba tanlanishi shart');
            }

            if ($supplierId && $otherSourceId) {
                $validator->errors()->add('supplier_id', 'Faqat ta\'minotchi yoki faqat boshqa manba tanlanishi mumkin');
            }

            // Supplier bo'lsa, type SUPPLIER_ bo'lishi kerak
            if ($supplierId && !str_starts_with($type, 'SUPPLIER_')) {
                $validator->errors()->add('type', 'Ta\'minotchi tanlanganda faqat SUPPLIER_INPUT yoki SUPPLIER_OUTPUT turini tanlashingiz mumkin');
            }

            // Other source bo'lsa, type OTHER_ bo'lishi kerak
            if ($otherSourceId && !str_starts_with($type, 'OTHER_')) {
                $validator->errors()->add('type', 'Boshqa manba tanlanganda faqat OTHER_INPUT yoki OTHER_OUTPUT turini tanlashingiz mumkin');
            }

            // ID majburiy bo'lishi kerak, faqat action "add" bo'lsa ixtiyoriy
            foreach ($products as $index => $product) {
                $action = $product['action'] ?? '';
                $id = $product['id'] ?? null;

                if ($action !== 'add' && !$id) {
                    $validator->errors()->add("products.{$index}.id", 'Faktura mahsulot idsi bo\'lishi shart');
                }
            }

            // Mahsulotlar uchun price faqat SUPPLIER_INPUT yoki OTHER_INPUT bo'lganda required
            foreach ($products as $index => $product) {
                // Mahsulotlar uchun price input_price dan kichik bo'lishi mumkin emas
                if (isset($product['price']) && isset($product['input_price'])) {
                    if ($product['price'] < $product['input_price']) {
                        $validator->errors()->add("products.{$index}.price", 'Mahsulot sotish narxi kirim narxidan kichik bo\'lishi mumkin emas');
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'supplier_id.numeric' => 'Ta\'minotchi ID noto\'g\'ri formatda',
            'supplier_id.exists' => 'Bunday Ta\'minotchi mavjud emas',
            'other_source_id.numeric' => 'Boshqa manba ID noto\'g\'ri formatda',
            'other_source_id.exists' => 'Bunday boshqa manba mavjud emas',
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
            'products.*.price.required' => 'Mahsulot narxi kiritilishi kerak (faqat SUPPLIER_INPUT yoki OTHER_INPUT uchun)',
            'products.*.price.numeric' => 'Mahsulot narxi raqam bo\'lishi kerak',
            'products.*.price.gte' => 'Mahsulot narxi musbat bo\'lishi kerak',
            'products.*.action.required' => 'Mahsulot actioni  bo\'lishi shart',
            'products.*.action.string' => 'Mahsulot actioni string bo\'lishi kerak',
            'products.*.action.in' => 'Mahsulot actioni faqat normal, add, edit yoki delete bo\'lishi kerak',
            'products.*.id.required' => 'Faktura mahsulot idsi bo\'lishi shart',
            'date.required' => 'Sana kiritilishi shart',
            'date.date_format' => 'Sana dd.mm.yyyy formatida bo\'lishi kerak',
            'products.*.input_price.required' => 'Mahsulot kirim narxi kiritilishi kerak',
            'products.*.input_price.numeric' => 'Mahsulot kirim narxi raqam bo\'lishi kerak',
            'products.*.input_price.gte' => 'Mahsulot kirim narxi musbat bo\'lishi kerak',
        ];
    }
}
