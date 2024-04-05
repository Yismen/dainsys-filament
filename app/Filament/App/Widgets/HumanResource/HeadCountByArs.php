<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Services\HC\ByArs;
use Filament\Widgets\ChartWidget;
use App\Filament\App\Widgets\Traits\HasColors;
use App\Filament\App\Widgets\Traits\HasMaxHeight;

class HeadCountByArs extends ChartWidget
{
    use HasColors;
    use HasMaxHeight;

    protected static ?string $heading = 'Head Count by Ars';

    protected function getData(): array
    {
        $service = (new ByArs())->count();

        return [
            'datasets' => [
                [
                    'label' => 'Employees By Ars',
                    'data' => $service->pluck('employees_count'),
                    'backgroundColor' => $this->getManyColors($service->pluck('name')->count()),
                ],
            ],
            'labels' => $service->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
