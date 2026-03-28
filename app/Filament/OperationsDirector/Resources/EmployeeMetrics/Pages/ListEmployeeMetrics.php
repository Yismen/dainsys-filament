<?php

namespace App\Filament\OperationsDirector\Resources\EmployeeMetrics\Pages;

use App\Filament\OperationsDirector\Resources\EmployeeMetrics\EmployeeMetricsResource;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeMetrics extends ListRecords
{
    protected static string $resource = EmployeeMetricsResource::class;
}
