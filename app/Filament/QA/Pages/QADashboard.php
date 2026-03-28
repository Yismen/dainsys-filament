<?php

namespace App\Filament\QA\Pages;

use App\Filament\QA\Widgets\QADisputeQueueTable;
use App\Filament\QA\Widgets\QAStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class QADashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            QAStatsOverview::class,
            QADisputeQueueTable::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
