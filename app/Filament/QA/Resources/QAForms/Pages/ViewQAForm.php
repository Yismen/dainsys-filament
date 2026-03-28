<?php

namespace App\Filament\QA\Resources\QAForms\Pages;

use App\Filament\QA\Resources\QAForms\QAFormResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQAForm extends ViewRecord
{
    protected static string $resource = QAFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
