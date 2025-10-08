<?php

namespace App\Filament\App\Resources\DowntimeReasonResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\App\Resources\DowntimeReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDowntimeReason extends EditRecord
{
    protected static string $resource = DowntimeReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
