<?php

namespace App\Filament\Workforce\Resources\Downtimes\Pages;

use App\Filament\Actions\AproveDowntimeAction;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Workforce\Resources\Downtimes\DowntimeResource;

class EditDowntime extends EditRecord
{
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            AproveDowntimeAction::make(),
        ];
    }
}
