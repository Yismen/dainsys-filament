<?php

namespace App\Filament\App\Widgets\HumanResource;

use Filament\Widgets\ChartWidget;

class MonthlyAttrition extends ChartWidget
{
    protected ?string $heading = 'Montly Attrition';

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
