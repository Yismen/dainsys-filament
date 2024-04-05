<?php

namespace App\Filament\App\Widgets\HumanResource;

use Filament\Widgets\ChartWidget;

class MonthlyEmployees extends ChartWidget
{
    protected static ?string $heading = 'Employees By Month';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
