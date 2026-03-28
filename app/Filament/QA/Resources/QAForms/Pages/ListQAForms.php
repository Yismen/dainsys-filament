<?php

namespace App\Filament\QA\Resources\QAForms\Pages;

use App\Filament\QA\Resources\QAForms\QAFormResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQAForms extends ListRecords
{
    protected static string $resource = QAFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
