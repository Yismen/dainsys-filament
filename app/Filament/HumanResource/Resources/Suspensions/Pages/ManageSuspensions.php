<?php

namespace App\Filament\HumanResource\Resources\Suspensions\Pages;

use App\Filament\HumanResource\Resources\Suspensions\SuspensionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSuspensions extends ManageRecords
{
    protected static string $resource = SuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
