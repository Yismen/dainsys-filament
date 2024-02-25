<?php

namespace App\Filament\HumanResource\Resources\SuspensionResource\Pages;

use App\Filament\HumanResource\Resources\SuspensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSuspension extends ViewRecord
{
    protected static string $resource = SuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
