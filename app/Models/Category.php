<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use Sortable;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $sortable = ['id', 'name', 'is_active'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
