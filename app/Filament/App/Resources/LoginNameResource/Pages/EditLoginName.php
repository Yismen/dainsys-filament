<?php

namespace App\Filament\App\Resources\LoginNameResource\Pages;

use App\Filament\App\Resources\LoginNameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoginName extends EditRecord
{
    protected static string $resource = LoginNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
