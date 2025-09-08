<?php

namespace App\Modules\Trade\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TradeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'client' => $this->client ? [
                'id' => $this->client->id,
                'name' => $this->client->name,
            ] : null,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'full_name' => $this->user->full_name,
            ] : null,
            'commentary' => $this->commentary,
            'products_count' => $this->products_count,
            'total_price' => $this->total_price,
            'updated_at' => $this->updated_at,
            'date' => $this->date,
            'type' => $this->type,
            'trade_products' => $this->whenLoaded('tradeProducts', function () {
                return $this->tradeProducts->map(function ($tradeProduct) {
                    return [
                        'id' => $tradeProduct->id,
                        'product_id' => $tradeProduct->product_id,
                        'count' => $tradeProduct->count,
                        'price' => $tradeProduct->price,
                        'total_price' => $tradeProduct->total_price,
                        'product' => $tradeProduct->product ? [
                            'id' => $tradeProduct->product->id,
                            'name' => $tradeProduct->product->name,
                            'residue' => $tradeProduct->product->residue,
                            'category' => $tradeProduct->product->category ? [
                                'id' => $tradeProduct->product->category->id,
                                'name' => $tradeProduct->product->category->name,
                            ] : null,
                        ] : null,
                    ];
                });
            }),
        ];
    }
}
