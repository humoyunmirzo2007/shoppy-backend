<?php

namespace App\Models;

use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use Sortable;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $sortable = ['id', 'first_name', 'middle_name', 'last_name', 'phone_number', 'debt', 'is_active', 'telegram_id', 'telegram_username'];

    /**
     * To'liq ismni qaytarish
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);
        return implode(' ', $parts);
    }
}
