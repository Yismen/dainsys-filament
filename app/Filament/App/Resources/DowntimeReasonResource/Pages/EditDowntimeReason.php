<?php

namespace App\Filament\App\Resources\DowntimeReasonResource\Pages;

use App\Filament\App\Resources\DowntimeReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDowntimeReason extends EditRecord
{
    protected static string $resource = DowntimeReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
