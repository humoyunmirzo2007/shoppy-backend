<?php

namespace App\Modules\Warehouse\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierCalculationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => abs($this->value),
            'type' => $this->type,
            'date' => Carbon::parse($this->date)->format('d.m.Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d.m.Y, H:i:s'),
        ];
    }
}
