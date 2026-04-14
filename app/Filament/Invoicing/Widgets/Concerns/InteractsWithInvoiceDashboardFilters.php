<?php

namespace App\Filament\Invoicing\Widgets\Concerns;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait InteractsWithInvoiceDashboardFilters
{
    use InteractsWithPageFilters;

    protected function applyDashboardFiltersToInvoiceQuery(Builder $query): Builder
    {
        $startDate = $this->getDashboardStartDate();
        $endDate = $this->getDashboardEndDate();
        $clientId = $this->getDashboardFilter('client_id');
        $projectId = $this->getDashboardFilter('project_id');

        return $query
            ->when($startDate, fn (Builder $builder): Builder => $builder->whereDate('date', '>=', $startDate))
            ->when($endDate, fn (Builder $builder): Builder => $builder->whereDate('date', '<=', $endDate))
            ->when(
                filled($clientId),
                fn (Builder $builder): Builder => $builder->whereHas('project', fn (Builder $projectQuery): Builder => $projectQuery->where('client_id', $clientId)),
            )
            ->when(
                filled($projectId),
                fn (Builder $builder): Builder => $builder->where('project_id', $projectId),
            );
    }

    protected function getDashboardStartDate(): ?Carbon
    {
        $startDate = $this->getDashboardFilter('start_date');

        if (blank($startDate)) {
            return null;
        }

        return Carbon::parse((string) $startDate)->startOfDay();
    }

    protected function getDashboardEndDate(): ?Carbon
    {
        $endDate = $this->getDashboardFilter('end_date');

        if (blank($endDate)) {
            return null;
        }

        return Carbon::parse((string) $endDate)->endOfDay();
    }

    protected function getDashboardFilter(string $key): mixed
    {
        return $this->pageFilters[$key] ?? $this->filters[$key] ?? null;
    }
}
