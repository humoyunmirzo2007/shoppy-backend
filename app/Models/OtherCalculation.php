<?php

namespace App\Models;

use App\Modules\Cashbox\Enums\OtherCalculationTypesEnum;
use App\Traits\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherCalculation extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'user_id',
        'payment_id',
        'type',
        'value',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'value' => 'decimal:2',
        'type' => OtherCalculationTypesEnum::class
    ];

    protected $hidden = ['created_at'];

    protected $sortable = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function getValueAttribute($value)
    {
        return abs($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)
            ->timezone(config('app.timezone'))
            ->format('d.m.Y, H:i:s');
    }
}
