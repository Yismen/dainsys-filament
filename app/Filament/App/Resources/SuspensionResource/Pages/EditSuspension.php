<?php

namespace App\Filament\App\Resources\SuspensionResource\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\App\Resources\SuspensionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuspension extends EditRecord
{
    protected static string $resource = SuspensionResource::class;

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
