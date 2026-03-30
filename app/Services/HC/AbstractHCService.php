<?php

namespace App\Services\HC;

use App\Services\Traits\HasFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

abstract class AbstractHCService implements HCContract
{
    use HasFilters;

    protected Builder $query;

    protected array $filters = [];

    public function __construct()
    {
        $this->query = $this->builder();
    }

    abstract protected function model(): Model;

    public function count(): Collection
    {
        $items = Cache::rememberForever(
            $this->cacheKey('hc_count_by_'),
            function (): array {
                $records = $this->parseFilters($this->filters, $this->query)
                    ->withCount(['employees' => fn ($q) => $q->notInactive()])
                    ->get();

                return $records
                    ->map(fn ($record): array => [
                        'id' => (int) $record->id,
                        'name' => (string) $record->name,
                        'employees_count' => (int) ($record->employees_count ?? 0),
                    ])
                    ->values()
                    ->all();
            }
        );

        return collect($items);
    }

    public function list(): Collection
    {
        $items = Cache::rememberForever(
            $this->cacheKey('hc_list_by_'),
            function (): array {
                $records = $this->parseFilters($this->filters, $this->query)
                    ->with(['employees' => fn ($q) => $q->notInactive()])
                    ->get();

                return $records
                    ->map(fn ($record): array => [
                        'id' => (int) $record->id,
                        'name' => (string) $record->name,
                        'employees' => $record->employees
                            ->map(fn ($emp): array => $emp->toArray())
                            ->values()
                            ->all(),
                    ])
                    ->values()
                    ->all();
            }
        );

        return collect($items);
    }

    public function constrain($callback): self
    {
        return $this;
    }

    protected function builder(): Builder
    {
        return $this->model()
            ->groupBy('name')
            ->orderBy('name')
            ->select(['name', 'id'])
            ->whereHas('employees', function ($query): void {
                $query->notInactive();
            });
    }

    protected function cacheKey(string $type): string
    {
        $class = get_class($this->model());

        $name = str($class)->replace('\\', ' ')->snake();

        $filters = implode('-', $this->filters);

        return "{$type}_{$name}_{$filters}";
    }
}
