<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Atribut qiymatlari bilan bog'lanish
     */
    public function attributeValues(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
