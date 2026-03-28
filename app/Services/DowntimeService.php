<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Downtime;
use App\Models\Employee;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Date;

class DowntimeService
{
    public static function create(string|int $employeeId, string|Date $date, string|int $campaignId, string|int $downtimeReasonId, float $totalTime, ?string $comment = null)
    {
        $employee = Employee::findOrFail($employeeId);
        $totalTimeAttempt = $employee->downtimes()
            ->whereDate('date', $date)
            ->sum('total_time');

        if (
            Downtime::query()
                ->whereDate('date', $date)
                ->where('employee_id', $employeeId)
                ->where('campaign_id', $campaignId)
                ->where('downtime_reason_id', $downtimeReasonId)
                ->exists()
        ) {
            Notification::make()
                ->title("Downtime already exists for employee {$employee->full_name} on date {$date} for the selected campaign and reason.")
                ->warning()
                ->send();

            return;
        }

        if ($totalTimeAttempt + $totalTime > 13) {
            Notification::make()
                ->title("Cannot create downtime for {$employee->full_name} on date {$date} because it would exceed the maximum of 13 hours. Current total: {$totalTimeAttempt} hours.")
                ->warning()
                ->send();

            return;
        }

        $downtime = $employee->downtimes()->create([
            'date' => $date,
            'campaign_id' => $campaignId,
            'downtime_reason_id' => $downtimeReasonId,
            'total_time' => $totalTime,
        ]);

        Notification::make()
            ->title("{$totalTime} hours of downtimes created for {$employee->full_name} on date {$date}.")
            ->success()
            ->send();

        if ($comment) {
            Comment::query()->forceCreate([
                'text' => $comment,
                'commentable_id' => $downtime->id,
                'commentable_type' => Downtime::class,
            ]);
        }
    }
}
