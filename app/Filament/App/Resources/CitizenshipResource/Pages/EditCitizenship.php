<?php

namespace App\Filament\App\Resources\CitizenshipResource\Pages;

use App\Filament\App\Resources\CitizenshipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCitizenship extends EditRecord
{
    protected static string $resource = CitizenshipResource::class;

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
