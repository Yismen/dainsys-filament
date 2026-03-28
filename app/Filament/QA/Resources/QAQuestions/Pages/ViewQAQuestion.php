<?php

namespace App\Filament\QA\Resources\QAQuestions\Pages;

use App\Filament\QA\Resources\QAQuestions\QAQuestionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQAQuestion extends ViewRecord
{
    protected static string $resource = QAQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
