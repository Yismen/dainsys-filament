<?php

namespace App\Filament\App\Resources\DowntimeReasonResource\Pages;

use App\Filament\App\Resources\DowntimeReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDowntimeReasons extends ListRecords
{
    protected static string $resource = DowntimeReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
