<?php

namespace App\Models;

use App\Modules\Cashbox\Enums\CostTypesEnum;
use App\Modules\Cashbox\Enums\OtherCalculationTypesEnum;
use App\Modules\Cashbox\Enums\PaymentTypesEnum;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoneyOperation extends Model
{
    use HasFactory, Sortable;

    protected $guarded = [];

    protected $casts = [
        // Type can be either PaymentTypesEnum or CostTypesEnum values
        // We'll handle casting manually in accessors/mutators if needed
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function otherPaymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'other_payment_type_id');
    }

    public function costType(): BelongsTo
    {
        return $this->belongsTo(CostType::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function otherCalculationAsPayment()
    {
        return $this->hasOne(OtherCalculation::class, 'payment_id')
            ->where('type', OtherCalculationTypesEnum::OTHER_PAYMENT->value);
    }

    public function otherCalculationAsCost()
    {
        return $this->hasOne(OtherCalculation::class, 'cost_id')
            ->where('type', OtherCalculationTypesEnum::OTHER_COST->value);
    }

    // Scope-lar
    public function scopeInputs($query)
    {
        return $query->where('operation_type', 'input');
    }

    public function scopeOutputs($query)
    {
        return $query->where('operation_type', 'output');
    }
}
