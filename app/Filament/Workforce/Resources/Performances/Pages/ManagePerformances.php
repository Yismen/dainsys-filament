<?php

namespace App\Filament\Workforce\Resources\Performances\Pages;

use App\Filament\Workforce\Resources\Performances\PerformanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePerformances extends ManageRecords
{
    protected static string $resource = PerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
