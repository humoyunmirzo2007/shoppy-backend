<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use Sortable;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $sortable = ['id', 'name', 'is_active', 'created_at', 'updated_at'];

    /**
     * Mahsulotlar bilan bog'lanish
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
