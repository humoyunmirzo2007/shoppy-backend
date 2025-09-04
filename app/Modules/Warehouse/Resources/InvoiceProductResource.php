<?php


namespace App\Modules\Warehouse\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceProductResource extends JsonResource
{
    public function toArray($request)
    {
        $product = $this->product;

        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'price' => $this->price,
            'count' => $this->count,
            'total_price' => $this->total_price,
            'product_id' => $this->product_id,
            'product' => $product ? [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ] : null,
                'residue' => $product->residue ?? null,
            ] : null,
        ];
    }
}
