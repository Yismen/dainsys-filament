<?php

namespace App\Filament\Workforce\Resources\NightlyHours\Pages;

use App\Filament\Workforce\Resources\NightlyHours\NightlyHourResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditNightlyHour extends EditRecord
{
    protected static string $resource = NightlyHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
            DeleteAction::make(),
        ];
    }
}
