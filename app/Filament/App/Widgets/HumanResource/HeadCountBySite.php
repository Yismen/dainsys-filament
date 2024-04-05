<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Services\HC\BySite;
use Filament\Widgets\ChartWidget;
use App\Filament\App\Widgets\Traits\HasColors;
use App\Filament\App\Widgets\Traits\HasMaxHeight;

class HeadCountBySite extends ChartWidget
{
    use HasColors;
    use HasMaxHeight;

    protected static ?string $heading = 'Head Count by Site';

    protected function getData(): array
    {
        $service = (new BySite())->count();

        return [
            'datasets' => [
                [
                    // 'label' => 'Employees By Site',
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
