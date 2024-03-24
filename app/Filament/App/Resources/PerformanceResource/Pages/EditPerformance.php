<?php

namespace App\Filament\App\Resources\PerformanceResource\Pages;

use App\Filament\App\Resources\PerformanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerformance extends EditRecord
{
    protected static string $resource = PerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
