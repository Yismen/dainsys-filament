<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByPosition
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('position')) {
            $position = $this->request->input('position');

            $builder->whereHas('position', function ($positionBuilder) use ($position) {
                $positionBuilder
                    ->where('id', $position)
                    ->orWhere('name', 'like', $position);

            });
        }

        return $next($builder);
    }
}
