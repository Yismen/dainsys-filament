<?php

namespace App\Filament\App\Resources\TerminationReasonResource\Pages;

use App\Filament\App\Resources\TerminationReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTerminationReason extends ViewRecord
{
    protected static string $resource = TerminationReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
