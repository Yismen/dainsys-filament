<?php

namespace App\Filament\ProjectExecutive\Resources\Payrolls\Pages;

use App\Filament\ProjectExecutive\Resources\Payrolls\PayrollResource;
use Filament\Resources\Pages\ListRecords;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;
}
