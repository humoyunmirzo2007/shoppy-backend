<?php

namespace App\Modules\Trade\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientCalculationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'value' => abs($this->value),
            'type' => $this->type,
            'date' => Carbon::parse($this->date)->format('d.m.Y'),
            'trade_id' => $this->trade_id ?? null,
            'payment_id' => $this->payment_id ?? null,
            'updated_at' => Carbon::parse($this->updated_at)->format('d.m.Y, H:i:s'),
        ];
    }
}
