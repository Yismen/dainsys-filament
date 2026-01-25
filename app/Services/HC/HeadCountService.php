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

    // public function filters(array $filters): self
    // {
    //     foreach ($filters as $relationship => $value) {
    //         if ($value) {
    //             self::$model
    //             ->withCount('hires')
    //                 ->withWhereHas('hires', function ($hiresQuery) use ($relationship, $value) {
    //                         $hiresQuery->withWhereHas('employee', function ($employees) use ($relationship, $value) {
    //                             $employees
    //                                 ->notInactive()
    //                                 ;
    //                         });
    //                     });
    //         }
    //     }

    //     return self::$instance;
    // }

    public function get()
    {
        $query = self::$model;

        return $query
            ->withWhereHas('hires', function ($hireQuery) {
                $hireQuery->withWhereHas('employee', function ($query) {
                    $query->notInactive();
                });
            })
            ->withCount([
                'hires' => function ($hireQuery) {
                    $hireQuery->withWhereHas('employee', function ($query) {
                        $query->notInactive();
                    });
                }])
            ->get();
    }
}
