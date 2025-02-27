<?php

namespace App\Traits;


use Carbon\Carbon;

trait EnsureDateNotWeekend
{
    protected static function ensureNotWeekend(Carbon $date): Carbon
    {
        while ($date->isWeekend()) {
            $date->addDay();
        }

        return $date;
    }
}
