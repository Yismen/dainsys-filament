<?php

namespace App\Filament\Actions;

use App\Models\Downtime;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class AproveDowntimeAction
{
    public static function make(string $name = 'aprove'): Action
    {
        return Action::make($name)
            ->visible(fn(Downtime $record) => $record->aprover_id === null)
            ->color(Color::Yellow)
            ->icon(Heroicon::Key)
            ->requiresConfirmation()
            ->modalHeading(__('Are you sure you want to aprove this downtime?'))
            ->successNotificationTitle('Downtimes aproved!')
            ->authorizeIndividualRecords('aprove')
            ->deselectRecordsAfterCompletion()
            ->action(function (Downtime $record) {
                if($record->aprover_id === null) {
                    $record->aprove();
                }
             });
    }
}
