<?php

namespace App\Modules\Cashbox\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'full_name' => $this->user->full_name,
            ],
            'from_payment_type' => [
                'id' => $this->paymentType->id,
                'name' => $this->paymentType->name,
            ],
            'to_payment_type' => [
                'id' => $this->otherPaymentType->id,
                'name' => $this->otherPaymentType->name,
            ],
            'amount' => (float) $this->amount,
            'description' => $this->description,
            'date' => $this->date,
            'created_at' => $this->created_at?->format('d.m.Y H:i'),
            'updated_at' => $this->updated_at?->format('d.m.Y H:i'),
        ];
    }
}
