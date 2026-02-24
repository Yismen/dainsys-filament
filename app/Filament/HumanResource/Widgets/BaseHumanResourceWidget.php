<?php

namespace App\Filament\HumanResource\Widgets;

use App\Services\HC\HeadCountService;
use App\Traits\Filament\HasColors;
use App\Traits\Filament\HasMaxHeight;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Cache;

abstract class BaseHumanResourceWidget extends ChartWidget
{
    use HasColors;
    use HasMaxHeight;
    use InteractsWithPageFilters;

    protected ?string $pollingInterval = null;

    abstract protected function getModel(): string;

    protected function getData(): array
    {
        $filtersString = $this->buildFiltersString();

        $cacheKey = implode('_', [
            'hr_actives_headcount_by',
            class_basename($this->getModel()),
            'filters',
            $filtersString,
        ]);

        $service = Cache::rememberForever(
            $cacheKey,
            function () {
                return HeadCountService::make($this->getModel())
                    ->filters(filters: $this->filters ?? [])
                    ->get();
            }
        );

        return [
            'datasets' => [
                [
                    'label' => $this->getHeading(),
                    'data' => $service->pluck('employees_count'),
                    'backgroundColor' => $this->getManyColors($service->pluck('name')->count()),
                ],
            ],
            'labels' => $service->pluck('name'),
        ];
    }

    protected function buildFiltersString(): string
    {
        $filtersString = '';
        foreach ($this->filters ?? [] as $key => $value) {
            $filtersString .= implode('_', [
                $key,
                is_array($value) ? implode('_', $value) : $value,
            ]);
        }

        return $filtersString ?: 'no_filters';
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
