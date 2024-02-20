<?php

namespace App\Services\Attrition;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;

class HiredService extends BaseAttritionService
{
    protected function query(): Builder
    {
        return Employee::query()
            // ->forDefaultSites()
            // ->filter(request()->all())
            ->whereDate('hired_at', '>=', $this->date_from)
            ->whereDate('hired_at', '<=', $this->date_to);
    }
}
