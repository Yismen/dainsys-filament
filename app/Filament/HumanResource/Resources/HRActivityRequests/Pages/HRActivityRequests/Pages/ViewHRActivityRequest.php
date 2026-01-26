<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Pages\HRActivityRequests\Pages;

use App\Filament\HumanResource\Resources\HRActivityRequests\HRActivityRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHRActivityRequest extends ViewRecord
{
    protected static string $resource = HRActivityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
