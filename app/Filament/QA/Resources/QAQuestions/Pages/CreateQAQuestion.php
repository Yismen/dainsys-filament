<?php

namespace App\Filament\QA\Resources\QAQuestions\Pages;

use App\Filament\QA\Resources\QAQuestions\QAQuestionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateQAQuestion extends CreateRecord
{
    protected static string $resource = QAQuestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = Auth::id();

        return $data;
    }
}
