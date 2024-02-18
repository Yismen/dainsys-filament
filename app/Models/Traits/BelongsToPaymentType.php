<?php

namespace App\Models\Traits;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToPaymentType
{
    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
