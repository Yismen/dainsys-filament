<?php

namespace App\Filament\Invoicing\Widgets;

use App\Filament\Invoicing\Widgets\Concerns\InteractsWithInvoiceDashboardFilters;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class InvoiceSummaryStats extends StatsOverviewWidget
{
    use InteractsWithInvoiceDashboardFilters;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $query = $this->applyDashboardFiltersToInvoiceQuery(Invoice::query());

        $totalInvoiced = (float) (clone $query)->sum('total_amount');
        $totalPaid = (float) (clone $query)->sum('total_paid');
        $balancePending = (float) (clone $query)->sum('balance_pending');

        return [
            Stat::make(__('Total amount invoiced'), Number::currency($totalInvoiced))
                ->description(__('Sum of invoice totals'))
                ->color('primary'),
            Stat::make(__('Total amount paid'), Number::currency($totalPaid))
                ->description(__('Sum of paid totals'))
                ->color('success'),
            Stat::make(__('Balance pending'), Number::currency($balancePending))
                ->description(__('Outstanding balance'))
                ->color($balancePending > 0 ? 'danger' : 'success'),
        ];
    }
}
