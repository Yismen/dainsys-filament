<?php

namespace App\Filament\App\Widgets\HumanResource;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use App\Services\HC\HeadCountService;
use App\Filament\App\Widgets\Traits\HasColors;
use App\Filament\App\Widgets\Traits\HasMaxHeight;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

abstract class BaseHumanResourcesWidget extends ChartWidget
{
    use HasColors;
    use HasMaxHeight;
    use InteractsWithPageFilters;

    abstract protected function getModel(): string;

    protected function getData(): array
    {
        $service = HeadCountService::make($this->getModel())
            ->filters(['site' => $this->pageFilters['site'] ?? null])
            ->get();

        return [
            'datasets' => [
                [
                    'label' => $this->getHeading(),
                    'data' => $service->pluck('employees_count'),
                    'backgroundColor' => $this->getManyColors($service->pluck('name')->count())
                ],
            ],
            'labels' => $service->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array|RawJs|null
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top'
                ]
            ],
            'scales' => [
                'x' => [
                    'display' => false
                ],
                'y' => [
                    'display' => false
                ],
            ],
        ];
    }
}
