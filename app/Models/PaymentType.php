<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use Sortable;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $sortable = ['id', 'name',  'is_active'];
}
