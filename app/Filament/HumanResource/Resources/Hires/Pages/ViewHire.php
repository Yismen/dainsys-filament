<?php

namespace App\Filament\HumanResource\Resources\Hires\Pages;

use App\Filament\HumanResource\Resources\Hires\HireResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHire extends ViewRecord
{
    protected static string $resource = HireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
