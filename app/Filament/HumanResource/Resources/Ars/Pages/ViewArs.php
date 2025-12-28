<?php

namespace App\Filament\HumanResource\Resources\Ars\Pages;

use App\Filament\HumanResource\Resources\Ars\ArsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewArs extends ViewRecord
{
    protected static string $resource = ArsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
