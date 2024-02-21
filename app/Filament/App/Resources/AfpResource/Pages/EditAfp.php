<?php

namespace App\Filament\App\Resources\AfpResource\Pages;

use App\Filament\App\Resources\AfpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAfp extends EditRecord
{
    protected static string $resource = AfpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
