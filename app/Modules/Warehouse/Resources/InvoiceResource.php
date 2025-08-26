<?php


namespace App\Modules\Warehouse\Resources;

use App\Modules\Warehouse\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'supplier_id' => $this->supplier_id,
            'commentary' => $this->commentary,
            'updated_at' => $this->updated_at,
            'supplier' => [
                'id' => $this->supplier->id,
                'name' => $this->supplier->name,
            ],
            'user' => [
                'id' => $this->user->id,
                'full_name' => $this->user->full_name,
            ],
            'invoice_products' => InvoiceProductResource::collection($this->invoiceProducts),
        ];
    }
}
