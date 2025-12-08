<?php

namespace App\Filament\Workforce\Resources\OvernightHours\Pages;

use App\Filament\Workforce\Resources\OvernightHours\OvernightHourResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageOvernightHours extends ManageRecords
{
    protected static string $resource = OvernightHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
