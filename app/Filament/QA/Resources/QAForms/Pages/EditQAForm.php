<?php

namespace App\Filament\QA\Resources\QAForms\Pages;

use App\Filament\QA\Resources\QAForms\QAFormResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditQAForm extends EditRecord
{
    protected static string $resource = QAFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
