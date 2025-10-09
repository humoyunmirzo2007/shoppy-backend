<?php

namespace App\Models;

use App\Modules\Warehouse\Enums\SupplierCalculationTypesEnum;
use App\Traits\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierCalculation extends Model
{
    use Sortable;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    protected $hidden = ['created_at'];

    protected $sortable = ['id'];

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

    protected $casts = [
        'type' => SupplierCalculationTypesEnum::class,
    ];
}
