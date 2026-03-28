<?php

namespace App\Filament\OperationsDirector\Resources\Payrolls\Pages;

use App\Filament\OperationsDirector\Resources\Payrolls\PayrollResource;
use Filament\Resources\Pages\ListRecords;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;
}
