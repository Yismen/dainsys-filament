<?php

namespace App\Services\HC;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class HeadCountService
{
    protected static $instance;

    protected static $model;

    protected static $filters = [];

    public static function make(string|Model $model): self
    {
        self::$instance = new self;

        self::$model = (new $model)->query();

        self::$filters = [];

        return self::$instance;
    }

    public function filters(array $filters): self
    {
        self::$filters = $filters;

        return self::$instance;
    }

    public function get(): Collection
    {
        return $this->getResultQuery()
            ->get();
    }

    public function count(): int
    {
        return $this->getResultQuery()
            ->count();
    }

    protected function getResultQuery(): Builder
    {
        $query = self::$model;
        
        $resultQuery =  $query
            ->withCount('employees')
            ->withWhereHas('employees', function ($employeeQuery) {
                $employeeQuery
                    ->notInactive()
                    ->when(self::$filters['site'] ?? null, function ($employeeQuery) {
                        $employeeQuery->whereHas('site', function ($q) {
                            $q->whereIn('id', is_array(self::$filters['site']) ? self::$filters['site'] : [self::$filters['site']]);
                        });
                    })
                    ->when(self::$filters['project'] ?? null, function ($employeeQuery) {
                        $employeeQuery->whereHas('project', function ($q) {
                            $q->whereIn('id', is_array(self::$filters['project']) ? self::$filters['project'] : [self::$filters['project']]);
                        });
                    })
                    ->when(self::$filters['supervisor'] ?? null, function ($employeeQuery) {
                        $employeeQuery->whereHas('supervisor', function ($q) {
                            $q->whereIn('id', is_array(self::$filters['supervisor']) ? self::$filters['supervisor'] : [self::$filters['supervisor']]);
                        });
                    });
            });

        return $resultQuery;
    }
}
