<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use Sortable;

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    public function tradeProducts(): HasMany
    {
        return $this->hasMany(TradeProduct::class);
    }

    public function invoiceProducts(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    protected $hidden = ['created_at', 'updated_at'];

    protected $sortable = ['id', 'name', 'unit', 'category_id', 'is_active', 'price'];
}
