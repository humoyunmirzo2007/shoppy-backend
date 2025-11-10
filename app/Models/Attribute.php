<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use Sortable;

    protected $fillable = [
        'name_uz',
        'name_ru',
        'type',
        'is_active',
    ];

    protected $sortable = ['id', 'name_uz', 'name_ru', 'type', 'is_active', 'created_at', 'updated_at'];

    /**
     * Atribut qiymatlari bilan bog'lanish
     */
    public function attributeValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
