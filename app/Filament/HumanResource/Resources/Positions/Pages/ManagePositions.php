<?php

namespace App\Filament\HumanResource\Resources\Positions\Pages;

use App\Filament\HumanResource\Resources\Positions\PositionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePositions extends ManageRecords
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
