<?php

namespace App\Filament\App\Resources\PunchResource\Pages;

use App\Filament\App\Resources\PunchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPunch extends EditRecord
{
    protected static string $resource = PunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
