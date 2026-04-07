<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
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
}
