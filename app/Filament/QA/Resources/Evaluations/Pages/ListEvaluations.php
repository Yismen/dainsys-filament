<?php

namespace App\Filament\QA\Resources\Evaluations\Pages;

use App\Filament\QA\Resources\Evaluations\EvaluationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListEvaluations extends ListRecords
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => Gate::allows('createQAEvaluations')),
        ];
    }
}
