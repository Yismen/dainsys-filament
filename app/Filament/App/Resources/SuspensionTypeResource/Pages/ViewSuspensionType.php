<?php

namespace App\Filament\App\Resources\SuspensionTypeResource\Pages;

use App\Filament\App\Resources\SuspensionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSuspensionType extends ViewRecord
{
    protected static string $resource = SuspensionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
