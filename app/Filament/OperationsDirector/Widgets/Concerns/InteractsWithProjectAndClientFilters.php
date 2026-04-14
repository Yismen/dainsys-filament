<?php

namespace App\Filament\OperationsDirector\Widgets\Concerns;

use App\Models\Project;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

trait InteractsWithProjectAndClientFilters
{
    use InteractsWithPageFilters;

    /**
     * @return array<int>
     */
    protected function getSelectedProjectIdsFromPageFilters(): array
    {
        return $this->pageFilters['project'] ?? $this->filters['project'] ?? [];
    }

    /**
     * @return array<int>
     */
    protected function getSelectedClientIdsFromPageFilters(): array
    {
        return $this->pageFilters['client'] ?? $this->filters['client'] ?? [];
    }

    protected function hasProjectOrClientFiltersApplied(): bool
    {
        return $this->getSelectedProjectIdsFromPageFilters() !== []
            || $this->getSelectedClientIdsFromPageFilters() !== [];
    }

    /**
     * @return array<int>
     */
    protected function getFilteredProjectIds(): array
    {
        $selectedProjectIds = $this->getSelectedProjectIdsFromPageFilters();
        $selectedClientIds = $this->getSelectedClientIdsFromPageFilters();

        return Project::query()
            ->when(
                $selectedProjectIds !== [],
                fn ($query) => $query->whereIn('id', $selectedProjectIds),
            )
            ->when(
                $selectedClientIds !== [],
                fn ($query) => $query->whereIn('client_id', $selectedClientIds),
            )
            ->pluck('id')
            ->values()
            ->all();
    }
}
