<?php

namespace App\Filament\HumanResource\Resources\Terminations\Pages;

use App\Filament\HumanResource\Resources\Terminations\TerminationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTermination extends EditRecord
{
    protected static string $resource = TerminationResource::class;

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
