<?php

namespace App\Filament\App\Resources\ArsResource\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\App\Resources\ArsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArs extends EditRecord
{
    protected static string $resource = ArsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
