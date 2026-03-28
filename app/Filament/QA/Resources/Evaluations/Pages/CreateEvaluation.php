<?php

namespace App\Filament\QA\Resources\Evaluations\Pages;

use App\Filament\QA\Resources\Evaluations\EvaluationResource;
use App\Models\QAForm;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateEvaluation extends CreateRecord
{
    protected static string $resource = EvaluationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['evaluator_id'] = Auth::id();

        if (isset($data['qa_form_id']) && ! isset($data['threshold_percentage'])) {
            $data['threshold_percentage'] = QAForm::query()
                ->whereKey($data['qa_form_id'])
                ->value('passing_threshold_percentage');
        }

        return $data;
    }
}
