<?php

namespace App\Modules\Trade\Requests;

use App\Http\Requests\MainRequest;
use App\Modules\Trade\Enums\TradeTypesEnum;
use Illuminate\Validation\Rule;

class StoreTradeRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'numeric', 'exists:clients,id'],
            'commentary' => ['nullable', 'max:200'],
            'type' => ['required', Rule::enum(TradeTypesEnum::class)],
            'date' => ['required', 'date_format:d.m.Y'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.count' => ['required', 'numeric', 'gt:0'],
            'products.*.price' => ['required', 'numeric', 'gte:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Mijoz tanlash shart',
            'client_id.numeric' => 'Mijoz ID noto\'g\'ri formatda',
            'client_id.exists' => 'Bunday mijoz mavjud emas',
            'commentary.max' => 'Izoh 200 ta belgidan oshmasligi kerak',
            'type.required' => 'Savdo turini tanlash shart',
            'type.enum' => 'Savdo turini noto\'g\'ri',
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
            'date.required' => 'Sana kiritilishi shart',
            'date.date_format' => 'Sana dd.mm.yyyy formatida bo\'lishi kerak',
        ];
    }
}
