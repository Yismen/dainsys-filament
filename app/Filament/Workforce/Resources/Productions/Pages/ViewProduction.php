<?php

namespace App\Filament\Workforce\Resources\Productions\Pages;

use App\Filament\Workforce\Resources\Productions\ProductionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProduction extends ViewRecord
{
    protected static string $resource = ProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
