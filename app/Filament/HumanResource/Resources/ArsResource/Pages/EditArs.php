<?php

namespace App\Filament\HumanResource\Resources\ArsResource\Pages;

use App\Filament\HumanResource\Resources\ArsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArs extends EditRecord
{
    protected static string $resource = ArsResource::class;

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
