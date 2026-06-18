<?php

namespace App\Filament\HumanResource\Resources\Hires\Pages;

use App\Filament\HumanResource\Resources\Hires\HireResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHires extends ManageRecords
{
    protected static string $resource = HireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
