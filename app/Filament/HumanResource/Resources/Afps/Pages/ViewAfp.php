<?php

namespace App\Filament\HumanResource\Resources\Afps\Pages;

use App\Filament\HumanResource\Resources\Afps\AfpResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAfp extends ViewRecord
{
    protected static string $resource = AfpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
