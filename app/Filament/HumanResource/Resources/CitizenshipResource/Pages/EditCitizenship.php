<?php

namespace App\Filament\HumanResource\Resources\CitizenshipResource\Pages;

use App\Filament\HumanResource\Resources\CitizenshipResource;
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
