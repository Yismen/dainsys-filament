<?php

namespace App\Filament\HumanResource\Resources\Universals\Pages;

use App\Filament\HumanResource\Resources\Universals\UniversalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUniversal extends ViewRecord
{
    protected static string $resource = UniversalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
