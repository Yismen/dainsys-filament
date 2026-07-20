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
            Stat::make(__('filament.total_amount_invoiced'), Number::currency($totalInvoiced))
                ->description(__('filament.sum_invoice_totals'))
                ->color('primary'),
            Stat::make(__('filament.total_amount_paid'), Number::currency($totalPaid))
                ->description(__('filament.sum_paid_totals'))
                ->color('success'),
            Stat::make(__('filament.balance_pending'), Number::currency($balancePending))
                ->description(__('filament.outstanding_balance'))
                ->color($balancePending > 0 ? 'danger' : 'success'),
        ];
    }
}
