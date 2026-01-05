<?php

namespace App\Filament\HumanResource\Resources\Suspensions\Pages;

use App\Filament\HumanResource\Resources\Suspensions\SuspensionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSuspension extends ViewRecord
{
    protected static string $resource = SuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
