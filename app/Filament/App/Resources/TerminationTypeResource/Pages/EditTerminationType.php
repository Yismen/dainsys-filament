<?php

namespace App\Filament\App\Resources\TerminationTypeResource\Pages;

use App\Filament\App\Resources\TerminationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTerminationType extends EditRecord
{
    protected static string $resource = TerminationTypeResource::class;

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
