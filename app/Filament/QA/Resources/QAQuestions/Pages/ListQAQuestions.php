<?php

namespace App\Filament\QA\Resources\QAQuestions\Pages;

use App\Filament\QA\Resources\QAQuestions\QAQuestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQAQuestions extends ListRecords
{
    protected static string $resource = QAQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
