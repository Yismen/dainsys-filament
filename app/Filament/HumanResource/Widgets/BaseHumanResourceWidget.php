<?php

namespace App\Filament\HumanResource\Widgets;

use App\Filament\Traits\HasColors;
use App\Filament\Traits\HasMaxHeight;
use App\Services\HC\HeadCountService;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Cache;

abstract class BaseHumanResourceWidget extends ChartWidget
{
    use HasColors;
    use HasMaxHeight;
    use InteractsWithPageFilters;

    abstract protected function getModel(): string;

    protected function getData(): array
    {
        $service = Cache::rememberForever(
            'hr_actives_headcount_by_'.$this->getModel(),
            function () {
                return HeadCountService::make($this->getModel())
                    // ->filters(['site' => $this->filters['site'] ?? null])
                    ->get();
            }
        );

        return [
            'datasets' => [
                [
                    'label' => $this->getHeading(),
                    'data' => $service->pluck('hires_count'),
                    // 'data' => $service->pluck('employees_count'),
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

    protected function getOptions(): array|RawJs|null
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
