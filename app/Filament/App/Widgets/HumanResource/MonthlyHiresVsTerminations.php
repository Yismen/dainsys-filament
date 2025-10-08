<?php

namespace App\Filament\App\Widgets\HumanResource;

use Filament\Widgets\ChartWidget;

class MonthlyHiresVsTerminations extends ChartWidget
{
    protected ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
