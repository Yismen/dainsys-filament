<?php

namespace App\Filament\HumanResource\Resources\BankResource\Pages;

use App\Filament\HumanResource\Resources\BankResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanks extends ListRecords
{
    protected static string $resource = BankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
