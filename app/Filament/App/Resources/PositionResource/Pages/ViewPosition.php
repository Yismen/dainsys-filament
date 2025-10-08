<?php

namespace App\Filament\App\Resources\PositionResource\Pages;

use Filament\Actions\EditAction;
use App\Filament\App\Resources\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPosition extends ViewRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
