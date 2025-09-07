<?php

namespace App\Modules\Trade\Resources;

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
            'updated_at' => $this->updated_at,
        ];
    }
}
