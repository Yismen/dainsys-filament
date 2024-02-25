<?php

namespace App\Filament\HumanResource\Resources\TerminationResource\Pages;

use App\Filament\HumanResource\Resources\TerminationResource;
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
