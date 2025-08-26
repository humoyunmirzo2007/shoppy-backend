<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use Sortable;

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productResidue(): HasOne
    {
        return $this->hasOne(ProductResidue::class);
    }

    protected $hidden = ['created_at', 'updated_at'];

    protected $sortable = ['id', 'name', 'unit', 'category_id', 'is_active'];
}
