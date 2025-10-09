<?php

namespace App\Modules\Trade\Requests;

use App\Http\Requests\MainRequest;

class GetTradeByIdRequest extends MainRequest
{
    public function rules()
    {
        return [
            'trade_id' => ['required', 'integer', 'exists:trades,id'],
        ];
    }

    public function validationData()
    {
        return ['trade_id' => $this->route('id')];
    }

    public function messages()
    {
        return [
            'trade_id.required' => 'Savdo ID si majburiy',
            'trade_id.integer' => 'Savdo ID si son bo\'lishi kerak',
            'trade_id.exists' => 'Bunday savdo mavjud emas',
        ];
    }
}
