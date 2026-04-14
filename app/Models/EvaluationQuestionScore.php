<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use Database\Factories\EvaluationQuestionScoreFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'evaluation_id',
    'qa_question_id',
    'points_awarded',
    'max_points_snapshot',
    'evaluator_note',
])]
class EvaluationQuestionScore extends AppModel
{
    /** @use HasFactory<EvaluationQuestionScoreFactory> */
    use HasFactory;

    use SoftDeletes;

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QAQuestion::class, 'qa_question_id');
    }

    protected function casts(): array
    {
        return [
            'points_awarded' => 'integer',
            'max_points_snapshot' => 'integer',
        ];
    }
}
