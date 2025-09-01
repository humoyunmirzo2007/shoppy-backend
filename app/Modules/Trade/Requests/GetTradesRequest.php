<?php

namespace App\Modules\Trade\Requests;

use App\Http\Requests\MainRequest;

class GetTradesRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'sort' => ['nullable', 'array'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
            'filters' => ['nullable', 'array'],
            'filters.date' => ['nullable'],
            'filters.client_id' => ['nullable', 'integer', 'exists:clients,id'],
            'filters.price_from' => ['nullable', 'numeric'],
            'filters.price_to' => ['nullable', 'numeric'],
            'filters.from_date' => ['nullable'],
            'filters.to_date' => ['nullable'],
            'filters.user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'sort.array' => 'Saralash parametrlari noto\'g\'ri',
            'limit.integer' => 'Limit son bo\'lishi kerak',
            'limit.min' => 'Limit kamida 1 bo\'lishi kerak',
            'limit.max' => 'Limit ko\'pi bilan 50 bo\'lishi kerak',
            'page.integer' => 'Page son bo\'lishi kerak',
            'page.min' => 'Page kamida 1 bo\'lishi kerak',
            'filters.array' => 'Filterlar to\'g\'ri formatda yuborilishi kerak',
            'filters.client_id.integer' => 'Mijoz ID raqam bo\'lishi kerak',
            'filters.client_id.exists' => 'Bunday mijoz mavjud emas',
            'filters.price_from.numeric' => 'Narx (dan) raqam bo\'lishi kerak',
            'filters.price_to.numeric' => 'Narx (gacha) raqam bo\'lishi kerak',
            'filters.user_id.integer' => 'Foydalanuvchi ID raqam bo\'lishi kerak',
            'filters.user_id.exists' => 'Bunday foydalanuvchi mavjud emas',
        ];
    }
}
