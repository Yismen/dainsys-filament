<?php

namespace App\Filament\Workforce\Resources\NightlyHours\Pages;

use App\Filament\Workforce\Resources\NightlyHours\NightlyHourResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNightlyHours extends ListRecords
{
    protected static string $resource = NightlyHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
