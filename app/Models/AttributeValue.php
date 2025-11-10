<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeValue extends Model
{
    use Sortable;

    protected $fillable = [
        'attribute_id',
        'value_uz',
        'value_ru',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    protected $sortable = ['id', 'attribute_id', 'value_uz', 'value_ru', 'created_at', 'updated_at'];
}
