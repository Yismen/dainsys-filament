<?php

namespace App\Services\Attrition;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class ActivesEndService extends BaseAttritionService
{
    protected function query(): Builder
    {
        return Employee::query()
            ->where(function ($query): void {
                $query->current()
                    ->orWhereHas('terminations', function ($query): void {
                        $query->where('date', '>', $this->date_to);
                    });
            });
    }
}
