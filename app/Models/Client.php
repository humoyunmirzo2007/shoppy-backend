<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use Sortable;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $sortable = ['id', 'name', 'phone_number', 'debt', 'is_active', 'telegram_id', 'telegram_username'];
}
