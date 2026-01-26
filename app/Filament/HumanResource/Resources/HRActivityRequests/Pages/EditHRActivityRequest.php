<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Pages;

use App\Filament\HumanResource\Resources\HRActivityRequests\HRActivityRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditHRActivityRequest extends EditRecord
{
    protected static string $resource = HRActivityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
