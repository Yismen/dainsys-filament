<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
