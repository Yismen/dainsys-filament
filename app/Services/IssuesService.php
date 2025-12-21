<?php

namespace App\Services;

use App\Models\Employee;
use App\Services\Traits\HasFilters;
use Illuminate\Support\Facades\Cache;

class IssuesService
{
    use HasFilters;

    protected array $items = [];

    protected array $fiters = [];

    public function handle()
    {
        $this->items['missing_information_count'] = Cache::rememberForever($this->cacheKey('missing_information_count'), fn () => $this->parseFilters($this->filters, Employee::whereDoesntHave('information'))->notInactive()->count());
        $this->items['missing_supervisor_count'] = Cache::rememberForever($this->cacheKey('missing_supervisor_count'), fn () => $this->parseFilters($this->filters, Employee::whereDoesntHave('supervisor'))->notInactive()->count());
        $this->items['missing_afp_count'] = Cache::rememberForever($this->cacheKey('missing_afp_count'), fn () => $this->parseFilters($this->filters, Employee::whereDoesntHave('afp'))->notInactive()->count());
        $this->items['missing_ars_count'] = Cache::rememberForever($this->cacheKey('missing_ars_count'), fn () => $this->parseFilters($this->filters, Employee::whereDoesntHave('ars'))->notInactive()->count());

        return $this->items;
    }

    protected function cacheKey(string $name): string
    {
        $key = implode('_', [
            $name,
            implode('_', array_keys($this->filters)),
            implode('_', $this->filters),
        ]);

        return $key;
    }
}
