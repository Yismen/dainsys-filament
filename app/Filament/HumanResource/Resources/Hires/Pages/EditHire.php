<?php

namespace App\Filament\HumanResource\Resources\Hires\Pages;

use App\Filament\HumanResource\Resources\Hires\HireResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditHire extends EditRecord
{
    protected static string $resource = HireResource::class;

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
