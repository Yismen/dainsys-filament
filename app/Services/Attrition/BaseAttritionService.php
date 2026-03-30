<?php

namespace App\Services\Attrition;

use App\Services\Traits\HasFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

abstract class BaseAttritionService implements AttritionContract
{
    use HasFilters;

    protected Carbon $date_from;

    protected Carbon $date_to;

    protected array $filters;

    public function __construct(?Carbon $date_from = null, ?Carbon $date_to = null, array $filters = [])
    {
        $this->date_from = $date_from ?: now();
        $this->date_to = $date_to ?: now();
        $this->filters = $filters;
    }

    abstract protected function query(): Builder;

    public function count(): int
    {
        return Cache::rememberForever($this->getCacheKey(__FUNCTION__), function () {
            $query = $this->parseFilters($this->filters, $this->query());

            return $query->count();
        });
    }

    public function list(): Collection
    {
        $items = Cache::rememberForever($this->getCacheKey(__FUNCTION__), function (): array {
            $records = $this->parseFilters($this->filters, $this->query());

            return $records
                ->get()
                ->map(fn ($record): array => is_array($record) ? $record : $record->toArray())
                ->values()
                ->all();
        });

        return collect($items);
    }

    protected function getCacheKey(string $method): string
    {
        $name = implode('_', [
            str(get_class($this))->replace('\\', ' ')->lower()->snake(),
            "{$method}",
            $this->date_from->format('Y-m-d'),
            $this->date_to->format('Y-m-d'),
            implode('_', array_keys($this->filters)),
            implode('_', $this->filters),
        ]);

        return $name;
    }
}
