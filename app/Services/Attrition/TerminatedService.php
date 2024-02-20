<?php

namespace App\Services\Attrition;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;

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
