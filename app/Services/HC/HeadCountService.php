<?php

namespace App\Services\HC;

use Illuminate\Database\Eloquent\Model;

class HeadCountService
{
    protected static $instance;

    protected static $model;

    public static function make(string|Model $model): self
    {
        self::$instance = new self;

        self::$model = (new $model)->query();

        return self::$instance;
    }

    public function filters(array $filters): self
    {
        foreach ($filters as $relationship => $value) {
            if ($value) {
                self::$model->whereHas('employees', function ($employees) use ($relationship, $value) {
                    $employees
                        ->notInactive()
                        ->whereHas($relationship, function ($query) use ($value) {
                            $query->when(
                                is_array($value),
                                fn ($q) => $q->whereIn('id', $value),
                                fn ($q) => $q->where('id', $value)
                            );
                        });
                });
            }
        }

        return self::$instance;
    }

    public function get()
    {
        $query = self::$model;

        return $query->withCount(['employees' => function ($employeeQuery) {
            $employeeQuery->notInactive();
        }])->get();
    }
}
