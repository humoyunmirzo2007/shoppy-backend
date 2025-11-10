<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use Sortable;

    protected $guarded = [];

    protected $sortable = ['id', 'name_uz', 'name_ru', 'is_active', 'sort_order', 'created_at', 'updated_at'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function firstParent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'first_parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
