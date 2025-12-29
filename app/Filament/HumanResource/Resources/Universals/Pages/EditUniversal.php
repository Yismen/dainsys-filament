<?php

namespace App\Filament\HumanResource\Resources\Universals\Pages;

use App\Filament\HumanResource\Resources\Universals\UniversalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUniversal extends EditRecord
{
    protected static string $resource = UniversalResource::class;

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
