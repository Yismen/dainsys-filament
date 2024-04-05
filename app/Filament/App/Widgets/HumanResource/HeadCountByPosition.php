<?php

namespace App\Filament\App\Widgets\HumanResource;

use App\Services\HC\ByPosition;
use Filament\Widgets\ChartWidget;
use App\Filament\App\Widgets\Traits\HasColors;
use App\Filament\App\Widgets\Traits\HasMaxHeight;

class HeadCountByPosition extends ChartWidget
{
    use HasColors;
    use HasMaxHeight;

    protected static ?string $heading = 'Head Count by Position';

    protected function getData(): array
    {
        $service = (new ByPosition())->count();

        return [
            'datasets' => [
                [
                    // 'label' => 'Employees By Position',
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
