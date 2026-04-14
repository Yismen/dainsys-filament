<?php

namespace App\Filament\Invoicing\Widgets;

use App\Filament\Invoicing\Widgets\Concerns\InteractsWithInvoiceDashboardFilters;
use App\Models\Invoice;
use App\Traits\Filament\HasColors;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IncomeByMonthChart extends ChartWidget
{
    use InteractsWithInvoiceDashboardFilters;
    use HasColors;

    protected ?string $heading = 'Income by month';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected ?string $maxHeight = '320px';

    protected function getType(): string
    {
        return 'line';
    }

    public function getData(): array
    {
        $startMonth = ($this->getDashboardStartDate() ?? now()->startOfMonth()->subMonths(5))->startOfMonth();
        $endMonth = ($this->getDashboardEndDate() ?? now())->endOfMonth();

        $months = collect();
        $monthCursor = $startMonth->copy();

        while ($monthCursor->lte($endMonth)) {
            $months->push($monthCursor->copy());
            $monthCursor->addMonth();
        }

        $monthKeys = $months->map(fn (Carbon $month): string => $month->format('Y-m'));

        $invoices = $this->applyDashboardFiltersToInvoiceQuery(
            Invoice::query()
                ->whereBetween('date', [$startMonth, $endMonth])
        )->get(['date', 'total_amount', 'total_paid']);

        $aggregated = [];

        foreach ($invoices as $invoice) {
            if (! $invoice->date) {
                continue;
            }

            $monthKey = Carbon::parse((string) $invoice->date)->format('Y-m');

            $aggregated[$monthKey]['invoiced'] = ($aggregated[$monthKey]['invoiced'] ?? 0) + (float) $invoice->total_amount;
            $aggregated[$monthKey]['paid'] = ($aggregated[$monthKey]['paid'] ?? 0) + (float) $invoice->total_paid;
        }

        return [
            'labels' => $months->map(fn (Carbon $month): string => $month->format('M Y'))->toArray(),
            'datasets' => [
                $this->makeLineChartDataset(
                    __('Invoiced'),
                    $monthKeys
                        ->map(fn (string $monthKey): float => round((float) ($aggregated[$monthKey]['invoiced'] ?? 0), 2))
                        ->toArray(),
                    '#f59e0b',
                    [
                        'fill' => false,
                        'tension' => 0.3,
                    ],
                ),
                $this->makeLineChartDataset(
                    __('Paid'),
                    $monthKeys
                        ->map(fn (string $monthKey): float => round((float) ($aggregated[$monthKey]['paid'] ?? 0), 2))
                        ->toArray(),
                    '#22c55e',
                    [
                        'fill' => false,
                        'tension' => 0.3,
                    ],
                ),
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
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
        ];
    }
}
