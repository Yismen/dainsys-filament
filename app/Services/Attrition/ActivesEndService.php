<?php

namespace App\Services\Attrition;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

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
