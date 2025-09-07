<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cashbox extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'residue',
        'user_id',
        'payment_type_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'residue' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
