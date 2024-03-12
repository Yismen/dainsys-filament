<?php

namespace App\Filament\App\Resources\SuspensionTypeResource\Pages;

use App\Filament\App\Resources\SuspensionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuspensionType extends EditRecord
{
    protected static string $resource = SuspensionTypeResource::class;

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
