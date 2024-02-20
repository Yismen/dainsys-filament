<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\TerminationReason;

class TerminationReasonService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('termination_reasons_list', function () {
            return TerminationReason::orderBy('name')->pluck('name', 'id');
        });
    }
}
