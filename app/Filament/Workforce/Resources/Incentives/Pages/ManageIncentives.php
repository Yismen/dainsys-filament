<?php

namespace App\Filament\Workforce\Resources\Incentives\Pages;

use App\Filament\Workforce\Resources\Incentives\IncentiveResource;
use Filament\Resources\Pages\ManageRecords;

class ManageIncentives extends ManageRecords
{
    protected static string $resource = IncentiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
