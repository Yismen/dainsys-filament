<?php

namespace App\Filament\App\Resources\SupervisorResource\Pages;

use App\Filament\App\Resources\SupervisorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupervisor extends EditRecord
{
    protected static string $resource = SupervisorResource::class;

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
