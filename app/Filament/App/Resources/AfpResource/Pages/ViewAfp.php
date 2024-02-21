<?php

namespace App\Filament\App\Resources\AfpResource\Pages;

use App\Filament\App\Resources\AfpResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAfp extends ViewRecord
{
    protected static string $resource = AfpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
