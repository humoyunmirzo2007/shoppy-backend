<?php

namespace App\Models;

use App\Modules\Trade\Enums\TradeTypesEnum;
use App\Traits\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trade extends Model
{
    use Sortable;

    protected $guarded = [];

    protected $casts = [
        'type' => TradeTypesEnum::class,
        'history' => 'array',
    ];

    public function tradeProducts(): HasMany
    {
        return $this->hasMany(TradeProduct::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function clientCalculations(): HasMany
    {
        return $this->hasMany(ClientCalculation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)
            ->timezone(config('app.timezone'))
            ->format('d.m.Y, H:i:s');
    }

    protected $sortable = ['id', 'updated_at', 'client_id', 'products_count', 'total_price', 'user_id'];
}
