<?php

namespace App\Filament\HumanResource\Resources\Terminations\Pages;

use App\Filament\HumanResource\Resources\Terminations\TerminationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTermination extends ViewRecord
{
    protected static string $resource = TerminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
