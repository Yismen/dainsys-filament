<?php

namespace App\Services\Attrition;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class TerminatedService extends BaseAttritionService
{
    protected function query(): Builder
    {
        return Employee::with('termination')
            ->whereHas('terminations', function ($query) {
                return $query->whereDate('date', '>=', $this->date_from)
                    ->whereDate('date', '<=', $this->date_to);
            });
    }
}
