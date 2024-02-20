<?php

namespace App\Services\Attrition;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;

class ActivesEndService extends BaseAttritionService
{
    protected function query(): Builder
    {
        return Employee::where('hired_at', '<=', $this->date_to)
            ->where(function ($query) {
                $query->notInactive()
                    ->orWhereHas('terminations', function ($query) {
                        $query->where('date', '>', $this->date_to);
                    });
            });
    }
}
