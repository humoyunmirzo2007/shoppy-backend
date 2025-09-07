<?php

namespace App\Modules\Cashbox\Resources;

use App\Http\Resources\DefaultResource;

class CashboxResource extends DefaultResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'residue' => $this->residue,
            'user' => [
                'id' => $this->user->id,
                'full_name' => $this->user->full_name,
                'username' => $this->user->username,
            ],
            'payment_type' => [
                'id' => $this->paymentType->id,
                'name' => $this->paymentType->name,
                'is_active' => $this->paymentType->is_active,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
