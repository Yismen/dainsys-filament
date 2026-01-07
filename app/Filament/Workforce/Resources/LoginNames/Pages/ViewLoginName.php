<?php

namespace App\Filament\Workforce\Resources\LoginNames\Pages;

use App\Filament\Workforce\Resources\LoginNames\LoginNameResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLoginName extends ViewRecord
{
    protected static string $resource = LoginNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
