<?php

namespace App\Filament\HumanResource\Resources\SuspensionTypes\Pages;

use App\Filament\HumanResource\Resources\SuspensionTypes\SuspensionTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSuspensionType extends ViewRecord
{
    protected static string $resource = SuspensionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
