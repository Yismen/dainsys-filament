<?php

namespace App\Filament\OperationsDirector\Resources\Deductions\Pages;

use App\Filament\OperationsDirector\Resources\Deductions\DeductionResource;
use Filament\Resources\Pages\ListRecords;

class ListDeductions extends ListRecords
{
    protected static string $resource = DeductionResource::class;
}
