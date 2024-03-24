<?php

namespace App\Filament\App\Resources\UniversalResource\Pages;

use App\Filament\App\Resources\UniversalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniversal extends EditRecord
{
    protected static string $resource = UniversalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
