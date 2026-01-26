<?php

namespace App\Services\HC;

use Illuminate\Database\Eloquent\Model;

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

    public function get()
    {
        $query = self::$model;

        return $query
            ->notInactive()
            ->when(self::$filters['site'] ?? null, function ($query) {
                $query->whereIn('site_id', is_array(self::$filters['site']) ? self::$filters['site'] : [self::$filters['site']]);
            })
            ->when(self::$filters['project'] ?? null, function ($query) {
                $query->whereIn('project_id', is_array(self::$filters['project']) ? self::$filters['project'] : [self::$filters['project']]);
            })
            ->when(self::$filters['supervisor'] ?? null, function ($query) {
                $query->whereIn('supervisor_id', is_array(self::$filters['supervisor']) ? self::$filters['supervisor'] : [self::$filters['supervisor']]);
            })
            ->get();
    }
}
