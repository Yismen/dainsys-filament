<?php

namespace App\Filament\App\Resources\TerminationResource\Pages;

use App\Filament\App\Resources\TerminationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTermination extends ViewRecord
{
    protected static string $resource = TerminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
