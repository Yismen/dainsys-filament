<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Database\Factories\QAFormFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QAForm extends AppModel
{
    /** @use HasFactory<QAFormFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'qa_forms';

    protected $fillable = [
        'name',
        'project_id',
        'passing_threshold_percentage',
        'description',
        'created_by',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QAQuestion::class, 'qa_form_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'qa_form_id');
    }

    protected function casts(): array
    {
        return [
            'passing_threshold_percentage' => 'float',
        ];
    }
}
