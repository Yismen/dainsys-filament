<?php

namespace App\Filament\QA\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class QADashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return 2;
    }
}
