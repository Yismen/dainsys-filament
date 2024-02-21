<?php

namespace App\Filament\App\Resources\BankResource\Pages;

use App\Filament\App\Resources\BankResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBank extends ViewRecord
{
    protected static string $resource = BankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
