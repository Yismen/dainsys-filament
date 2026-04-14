<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Database\Factories\QAQuestionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'text',
    'qa_form_id',
    'author_id',
    'description',
    'max_points',
    'display_order',
    'is_active',
])]
#[Table(name: 'qa_questions')]
class QAQuestion extends AppModel
{
    /** @use HasFactory<QAQuestionFactory> */
    use HasFactory;

    use SoftDeletes;

    public function form(): BelongsTo
    {
        return $this->belongsTo(QAForm::class, 'qa_form_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(EvaluationQuestionScore::class, 'qa_question_id');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'max_points' => 'integer',
            'display_order' => 'integer',
        ];
    }
}
