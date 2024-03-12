<?php

namespace App\Filament\App\Resources\TerminationReasonResource\Pages;

use App\Filament\App\Resources\TerminationReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTerminationReason extends EditRecord
{
    protected static string $resource = TerminationReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
