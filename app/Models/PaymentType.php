<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use Sortable;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'residue' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $sortable = ['id', 'name', 'residue', 'is_active'];
}
