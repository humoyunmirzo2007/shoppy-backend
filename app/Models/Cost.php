<?php

namespace App\Models;

use App\Modules\Cashbox\Enums\CostTypesEnum;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cost extends Model
{
    use HasFactory, Sortable;

    protected $guarded = [];

    protected $casts = [
        'type' => CostTypesEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function costType(): BelongsTo
    {
        return $this->belongsTo(CostType::class);
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

    public function otherCalculation()
    {
        return $this->hasOne(OtherCalculation::class, 'cost_id')
            ->where('type', \App\Modules\Cashbox\Enums\OtherCalculationTypesEnum::OTHER_COST->value);
    }
}
