<?php

namespace App\Models;

use App\Modules\Trade\Enums\ClientCalculationTypesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'trade_id',
        'type',
        'value',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'value' => 'decimal:2',
        'type' => ClientCalculationTypesEnum::class
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }
}
