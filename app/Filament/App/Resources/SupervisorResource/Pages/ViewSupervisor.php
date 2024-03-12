<?php

namespace App\Filament\App\Resources\SupervisorResource\Pages;

use App\Filament\App\Resources\SupervisorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSupervisor extends ViewRecord
{
    protected static string $resource = SupervisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
