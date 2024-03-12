<?php

namespace App\Filament\App\Resources\TerminationTypeResource\Pages;

use App\Filament\App\Resources\TerminationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTerminationType extends ViewRecord
{
    protected static string $resource = TerminationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
