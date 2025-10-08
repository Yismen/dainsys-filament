<?php

namespace App\Filament\App\Resources\UniversalResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\App\Resources\UniversalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniversal extends EditRecord
{
    protected static string $resource = UniversalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
