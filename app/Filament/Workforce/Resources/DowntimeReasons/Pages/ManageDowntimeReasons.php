<?php

namespace App\Filament\Workforce\Resources\DowntimeReasons\Pages;

use App\Filament\Workforce\Resources\DowntimeReasons\DowntimeReasonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDowntimeReasons extends ManageRecords
{
    protected static string $resource = DowntimeReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
