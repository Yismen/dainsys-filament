<?php

namespace App\Services\Attrition;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class ActivesStartService extends BaseAttritionService
{
    protected function query(): Builder
    {
        return Employee::query()
            // ->where('hired_at', '<', $this->date_from)
            // ->filter(request()->all())
            // ->forDefaultSites()
            ->where(function ($query) {
                $query->notInactive()
                    ->orWhereHas('terminations', function ($query) {
                        $query->where('date', '>', $this->date_from);
                    });
            });
    }
}
