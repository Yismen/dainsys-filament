<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceAgent extends AppModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
        'phone',
        'email',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function invoices(): HasMany
    {
        // invoices table uses agent_id as foreign key
        return $this->hasMany(Invoice::class, 'agent_id', 'id');
    }
}
