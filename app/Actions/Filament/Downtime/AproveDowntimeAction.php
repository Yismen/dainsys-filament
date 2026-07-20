<?php

namespace App\Actions\Filament\Downtime;

use App\Models\Downtime;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class AproveDowntimeAction
{
    public static function make(string $name = 'aprove'): Action
    {
        return Action::make($name)
            ->visible(fn (Downtime $record) => $record->aprover_id === null)
            ->color(Color::Yellow)
            ->icon(Heroicon::Key)
            ->requiresConfirmation()
            ->modalHeading(__('filament.aprove_downtime_confirmation'))
            ->successNotificationTitle(__('filament.downtimes_approved_notification'))
            ->authorizeIndividualRecords('aprove')
            ->deselectRecordsAfterCompletion()
            ->action(function (Downtime $record): void {
                if ($record->aprover_id === null) {
                    $record->aprove();
                }
            });
    }
}
