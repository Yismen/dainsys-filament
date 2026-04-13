<?php

namespace App\Models;

use App\Exceptions\InvoiceOverpaymentException;
use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePayment extends AppModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'amount',
        'date',
        'reference',
        'images',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'images' => 'array',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    protected static function booted(): void
    {
        parent::booted();
        static::saving(function (InvoicePayment $payment) {
            $invoice = $payment->invoice;
            if (! $invoice) {
                return;
            }
            $currentBalance = (float) ($invoice->balance_pending ?? 0);
            $originalAmount = (float) $payment->getOriginal('amount') ?? 0.0;
            $newAmount = (float) $payment->amount;
            $maxAllowed = $currentBalance + $originalAmount;
            if ($newAmount > $maxAllowed) {
                throw new InvoiceOverpaymentException('Payment amount exceeds invoice balance pending.');
            }
        });
        static::saved(function (InvoicePayment $payment) {
            $invoice = $payment->invoice;
            if (! $invoice) {
                return;
            }

            $invoice->save();
        });
        static::deleted(function (InvoicePayment $payment) {
            $invoice = $payment->invoice;
            if ($invoice) {
                $invoice->save();
            }
        });
        static::restored(function (InvoicePayment $payment) {
            $invoice = $payment->invoice;
            if ($invoice) {
                $invoice->save();
            }
        });
    }
}
