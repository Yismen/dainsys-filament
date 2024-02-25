<?php

namespace App\Filament\HumanResource\Resources\SuspensionResource\Pages;

use App\Filament\HumanResource\Resources\SuspensionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuspension extends EditRecord
{
    protected static string $resource = SuspensionResource::class;

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
