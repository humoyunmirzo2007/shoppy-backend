<?php

namespace App\Models;

use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, Sortable;

    protected $guarded = [];

    protected $casts = [
        'type' => PaymentTypesEnum::class,
    ];

    public function cashbox(): BelongsTo
    {
        return $this->belongsTo(Cashbox::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
