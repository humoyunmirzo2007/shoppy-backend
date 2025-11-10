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
            'input_price' => $this->input_price,
            'wholesale_price' => $this->wholesale_price,
            'count' => $this->count,
            'total_price' => $this->total_price,
            'product_id' => $this->product_id,
            'product' => $product ? [
                'id' => $product->id,
                'name_uz' => $product->name_uz,
                'name_ru' => $product->name_ru,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name_uz' => $product->category->name_uz,
                    'name_ru' => $product->category->name_ru,
                ] : null,
                'residue' => $product->residue ?? null,
            ] : null,
        ];
    }
}
