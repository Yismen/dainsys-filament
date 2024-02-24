<?php

namespace App\Filament\HumanResource\Resources\PositionResource\Pages;

use App\Filament\HumanResource\Resources\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPosition extends ViewRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
