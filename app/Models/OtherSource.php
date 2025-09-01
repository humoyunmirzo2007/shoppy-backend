<?php

namespace App\Models;

use App\Modules\Information\Enums\OtherSourceTypesEnum;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherSource extends Model
{
    use Sortable;

    protected $fillable = [
        'name',
        'type',
        'is_active'
    ];

    protected $casts = [
        'type' => OtherSourceTypesEnum::class,
    ];
}
