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

    /**
     * Brend bilan bog'lanish
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Mahsulot atributlari bilan bog'lanish
     */
    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    protected $hidden = ['created_at', 'updated_at'];

    protected $sortable = ['id', 'name_uz', 'name_ru', 'unit', 'category_id', 'brand_id', 'is_active', 'price', 'wholesale_price'];
}
