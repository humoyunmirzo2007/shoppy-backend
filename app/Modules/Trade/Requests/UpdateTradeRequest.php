<?php

namespace App\Modules\Trade\Requests;

use App\Http\Requests\MainRequest;

class UpdateTradeRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'numeric', 'exists:clients,id'],
            'commentary' => ['nullable', 'max:200'],
            'type' => ['required', 'in:TRADE,RETURN_PRODUCT'],
            'date' => ['required', 'date_format:d.m.Y'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.count' => ['required', 'numeric', 'gt:0'],
            'products.*.price' => ['required', 'numeric', 'gte:0'],
            'products.*.action' => ['required', 'string', 'in:normal,add,edit,delete'],
            'products.*.id' => ['nullable', 'numeric'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $products = $this->input('products', []);

            foreach ($products as $index => $product) {
                $action = $product['action'] ?? '';
                $id = $product['id'] ?? null;

                if ($action !== 'add' && ! $id) {
                    $validator->errors()->add("products.{$index}.id", 'Savdo mahsulot idsi bo\'lishi shart');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Mijoz tanlash shart',
            'client_id.numeric' => 'Mijoz ID noto\'g\'ri formatda',
            'client_id.exists' => 'Bunday mijoz mavjud emas',
            'commentary.max' => 'Izoh 200 ta belgidan oshmasligi kerak',
            'type.required' => 'Savdo turini tanlash shart',
            'type.in' => 'Savdo turini noto\'g\'ri',
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
            'products.*.action.required' => 'Mahsulot actioni bo\'lishi shart',
            'products.*.action.string' => 'Mahsulot actioni string bo\'lishi kerak',
            'products.*.action.in' => 'Mahsulot actioni faqat normal, add, edit yoki delete bo\'lishi kerak',
            'products.*.id.required' => 'Savdo mahsulot idsi bo\'lishi shart',
            'date.required' => 'Sana kiritilishi shart',
            'date.date_format' => 'Sana dd.mm.yyyy formatida bo\'lishi kerak',
        ];
    }
}
