<?php

namespace App\Modules\Warehouse\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierCalculationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => abs($this->value),
            'type' => $this->type,
            'updated_at' => $this->updated_at,
        ];
    }
}
