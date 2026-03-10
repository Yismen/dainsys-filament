<?php

namespace App\Filament\Workforce\Resources\PayrollHours\Pages;

use App\Filament\Workforce\Resources\PayrollHours\PayrollHourResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePayrollHours extends ManageRecords
{
    protected static string $resource = PayrollHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
