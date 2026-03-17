<?php

namespace App\Filament\Supervisor\Resources\Deductions\Pages;

use App\Filament\Supervisor\Resources\Deductions\DeductionResource;
use Filament\Resources\Pages\ListRecords;

class ListDeductions extends ListRecords
{
    protected static string $resource = DeductionResource::class;
}
