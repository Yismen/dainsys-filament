<?php

namespace App\Filament\Employee\Resources\EmployeeMetrics\Pages;

use App\Filament\Employee\Resources\EmployeeMetrics\EmployeeMetricsResource;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeMetrics extends ListRecords
{
    protected static string $resource = EmployeeMetricsResource::class;
}
