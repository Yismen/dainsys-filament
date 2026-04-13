<?php

namespace App\Models;

use App\Enums\InvoiceStatuses;
use App\Exceptions\InvoiceCannotBeCancelledException;
use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class InvoiceCancellation extends AppModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'cancelled_by',
        'date',
        'reason',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (InvoiceCancellation $cancellation): void {
            if (blank($cancellation->cancelled_by)) {
                $cancellation->cancelled_by = Auth::id();
            }

            if (blank($cancellation->invoice_id)) {
                return;
            }

            $invoice = Invoice::query()->find($cancellation->invoice_id);

            if (! $invoice) {
                return;
            }

            $paidAmount = (float) $invoice->payments()->sum('amount');

            if ($paidAmount > 0) {
                throw new InvoiceCannotBeCancelledException('Only invoices without payments can be cancelled.');
            }
        });

        static::created(function (InvoiceCancellation $cancellation): void {
            $invoice = $cancellation->invoice;

            if (! $invoice) {
                return;
            }

            $invoice->status = InvoiceStatuses::Cancelled;
            $invoice->save();
        });
    }
}
