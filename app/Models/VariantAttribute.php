<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantAttribute extends Model
{
    use Sortable;

    protected $fillable = [
        'product_variant_id',
        'attribute_value_id',
    ];

    protected $sortable = ['id', 'product_variant_id', 'attribute_value_id', 'created_at', 'updated_at'];

    /**
     * Variant bilan bog'lanish
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Atribut qiymati bilan bog'lanish
     */
    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
