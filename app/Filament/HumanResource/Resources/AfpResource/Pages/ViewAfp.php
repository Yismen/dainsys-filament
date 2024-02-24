<?php

namespace App\Filament\HumanResource\Resources\AfpResource\Pages;

use App\Filament\HumanResource\Resources\AfpResource;
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
