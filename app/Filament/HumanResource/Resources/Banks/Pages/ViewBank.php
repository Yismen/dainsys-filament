<?php

namespace App\Filament\HumanResource\Resources\Banks\Pages;

use App\Filament\HumanResource\Resources\Banks\BankResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBank extends ViewRecord
{
    protected static string $resource = BankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
