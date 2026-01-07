<?php

namespace App\Filament\Workforce\Resources\Sources\Pages;

use App\Filament\Workforce\Resources\Sources\SourceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSource extends ViewRecord
{
    protected static string $resource = SourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
