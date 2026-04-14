<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Database\Factories\EvaluationStatusHistoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'evaluation_id',
    'from_status',
    'to_status',
    'changed_by',
    'change_comment',
    'metadata',
])]
class EvaluationStatusHistory extends AppModel
{
    /** @use HasFactory<EvaluationStatusHistoryFactory> */
    use HasFactory;

    use SoftDeletes;

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }
}
