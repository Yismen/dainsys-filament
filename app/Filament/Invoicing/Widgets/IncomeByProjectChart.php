<?php

namespace App\Filament\Invoicing\Widgets;

use App\Filament\Invoicing\Widgets\Concerns\InteractsWithInvoiceDashboardFilters;
use App\Models\Invoice;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class IncomeByProjectChart extends ChartWidget
{
    use InteractsWithInvoiceDashboardFilters;

    protected ?string $heading = 'Income by project';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected ?string $maxHeight = '320px';

    protected function getType(): string
    {
        return 'bar';
    }

    public function getData(): array
    {
        /** @var Builder $query */
        $query = Invoice::query()->whereNotNull('project_id');

        $invoices = $this->applyDashboardFiltersToInvoiceQuery($query)
            ->with('project:id,name')
            ->get(['project_id', 'total_amount', 'total_paid']);

        $grouped = $invoices
            ->groupBy('project_id')
            ->map(function ($rows): array {
                $first = $rows->first();

                return [
                    'label' => $first?->project?->name ?? __('Unknown'),
                    'invoiced' => round((float) $rows->sum('total_amount'), 2),
                    'paid' => round((float) $rows->sum('total_paid'), 2),
                ];
            })
            ->sortByDesc('invoiced')
            ->take(10)
            ->values();

        return [
            'labels' => $grouped->pluck('label')->toArray(),
            'datasets' => [
                [
                    'label' => __('Invoiced'),
                    'data' => $grouped->pluck('invoiced')->toArray(),
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#f59e0b',
                ],
                [
                    'label' => __('Paid'),
                    'data' => $grouped->pluck('paid')->toArray(),
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#22c55e',
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'ticks' => [
                        'maxRotation' => 0,
                        'autoSkip' => true,
                    ],
                ],
            ],
        ];
    }
}
