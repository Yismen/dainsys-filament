<?php

namespace App\Filament\App\Resources\ArsResource\Pages;

use App\Filament\App\Resources\ArsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewArs extends ViewRecord
{
    protected static string $resource = ArsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
