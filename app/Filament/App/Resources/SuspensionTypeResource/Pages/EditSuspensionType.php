<?php

namespace App\Filament\App\Resources\SuspensionTypeResource\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\App\Resources\SuspensionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuspensionType extends EditRecord
{
    protected static string $resource = SuspensionTypeResource::class;

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
