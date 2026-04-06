<?php

namespace App\Filament\ProjectExecutive\Widgets\Concerns;

use Filament\Widgets\Concerns\InteractsWithPageFilters;

trait InteractsWithProjectFilter
{
    use InteractsWithPageFilters;

    /**
     * @return array<int>
     */
    protected function getSelectedProjectIdsFromPageFilters(): array
    {
        return $this->pageFilters['project'] ?? $this->filters['project'] ?? [];
    }
}
