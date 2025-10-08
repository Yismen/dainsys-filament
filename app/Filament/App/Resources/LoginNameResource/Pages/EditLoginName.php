<?php

namespace App\Filament\App\Resources\LoginNameResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\App\Resources\LoginNameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoginName extends EditRecord
{
    protected static string $resource = LoginNameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
