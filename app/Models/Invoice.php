<?php

namespace App\Models;

use App\Enums\InvoiceStatuses;
use App\Exceptions\InvoiceZeroSubtotalException;
use App\Models\BaseModels\AppModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends AppModel
{
    use HasFactory;

    protected $fillable = [
        'number',
        'date',
        'project_id',
        'agent_id',
        'campaign_id',
        'items',
        'subtotal_amount',
        'tax_amount',
        'total_amount',
        'total_paid',
        'balance_pending',
        'status',
        'due_date',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(InvoiceAgent::class, 'agent_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    protected static function booted(): void
    {
        parent::booted();
        static::saving(function (Invoice $invoice) {
            // Recalculate subtotal from items array
            $subtotal = 0.0;
            if (is_array($invoice->items)) {
                foreach ($invoice->items as $it) {
                    if (is_array($it) && isset($it['price'])) {
                        $subtotal += (float) $it['price'];
                    }
                }
            }
            if ($subtotal < 0) {
                $subtotal = 0.0;
            }
            $invoice->subtotal_amount = $subtotal;

            // Tax and total
            $tax = (float) ($invoice->tax_amount ?? 0.0);
            $invoice->total_amount = max(0.0, $invoice->subtotal_amount + $tax);

            // Total paid (sum of payments)
            $totalPaid = 0.0;
            try {
                $totalPaid = (float) $invoice->payments()->sum('amount');
            } catch (\Throwable $e) {
                $totalPaid = 0.0;
            }
            $invoice->total_paid = $totalPaid;

            // Balance pending
            $invoice->balance_pending = max(0.0, $invoice->total_amount - $invoice->total_paid);

            // Due date: if missing and there is a total amount, default to 30 days after invoice date
            if (! $invoice->due_date && $invoice->total_amount > 0) {
                $date = $invoice->date ? Carbon::parse((string) $invoice->date) : Carbon::now();
                $invoice->due_date = (clone $date)->addDays(30);
            }

            // Prevent creating an invoice with zero subtotal
            if (! $invoice->exists && $invoice->subtotal_amount <= 0) {
                throw new InvoiceZeroSubtotalException('Cannot create invoice with zero subtotal.');
            }

            $invoice->status = $invoice->determineStatus()->value;
        });
        static::creating(function (Invoice $invoice) {
            // Build client and project prefixes
            $clientName = '';
            $projectName = '';
            if ($invoice->project_id) {
                $project = Project::find($invoice->project_id);
                if ($project) {
                    $projectName = $project->name ?? '';
                    $clientName = $project->client->name ?? '';
                }
            }
            $clientPrefix = self::nameToPrefix($clientName);
            $projectPrefix = self::nameToPrefix($projectName);
            $prefix = 'ECC_'.$clientPrefix.'_'.$projectPrefix;

            $latest = self::where('number', 'like', $prefix.'_%')->orderByDesc('number')->first();
            if ($latest) {
                $parts = explode('_', $latest->number);
                $suffix = end($parts);
                $seq = is_numeric($suffix) ? ((int) $suffix + 1) : 1;
            } else {
                $seq = 1;
            }
            $invoice->number = $prefix.'_'.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
        });
    }

    private static function nameToPrefix(string $name): string
    {
        $name = trim($name);
        // normalize separators to spaces
        $name = preg_replace('/[-_]+/', ' ', $name);
        // split into max 3 parts
        $parts = preg_split('/\s+/', $name, 3, PREG_SPLIT_NO_EMPTY);
        $count = count($parts);
        if ($count >= 3) {
            return strtoupper(substr($parts[0], 0, 1).substr($parts[1], 0, 1).substr($parts[2], 0, 1));
        } elseif ($count == 2) {
            return strtoupper(substr($parts[0], 0, 1).substr($parts[1], 0, 2));
        } elseif ($count == 1) {
            return strtoupper($parts[0]);
        }

        return '';
    }

    public function determineStatus(): InvoiceStatuses
    {
        // If explicitly cancelled via status field, respect it
        if (isset($this->status) && $this->status === InvoiceStatuses::Cancelled->value) {
            return InvoiceStatuses::Cancelled;
        }

        $balance = (float) ($this->balance_pending ?? 0.0);
        $paid = (float) ($this->total_paid ?? 0.0);
        $total = (float) ($this->total_amount ?? 0.0);

        // Fully paid
        if ($total > 0 && $balance <= 0) {
            return InvoiceStatuses::Paid;
        }

        // Partially paid: some payments exist but balance remains
        if ($balance > 0 && $paid > 0) {
            return InvoiceStatuses::PartiallyPaid;
        }

        // Overdue: balance remains and due_date is in the past
        if ($balance > 0 && ! empty($this->due_date)) {
            if (Carbon::parse((string) $this->due_date)->isPast()) {
                return InvoiceStatuses::Overdue;
            }
        }

        // Default to Pending
        return InvoiceStatuses::Pending;
    }
}
