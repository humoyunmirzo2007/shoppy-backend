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
            'other_source_id' => $this->other_source_id,
            'commentary' => $this->commentary,
            'updated_at' => $this->updated_at,
            'supplier' => $this->supplier ? [
                'id' => $this->supplier->id,
                'name' => $this->supplier->name,
            ] : null,
            'other_source' => $this->otherSource ? [
                'id' => $this->otherSource->id,
                'name' => $this->otherSource->name,
            ] : null,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'full_name' => $this->user->full_name,
            ] : null,
            'invoice_products' => InvoiceProductResource::collection($this->invoiceProducts),
            'date' => $this->date,
            'type' => $this->type,
        ];
    }
}
