<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeProduct extends Model
{
    use Sortable;

    protected $guarded = [];

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getCountAttribute($value)
    {
        return abs($value);
    }
}
