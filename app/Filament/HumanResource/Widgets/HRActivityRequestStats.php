<?php

namespace App\Filament\HumanResource\Widgets;

use App\Enums\HRActivityRequestStatuses;
use App\Filament\HumanResource\Resources\HRActivityRequests\HRActivityRequestResource;
use App\Models\HRActivityRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HRActivityRequestStats extends StatsOverviewWidget
{
    protected ?string $heading = 'HR Activity Requests';

    protected function getStats(): array
    {
        $filters = $this->filters ?? [];

        $query = HRActivityRequest::query();

        if (isset($filters['site']) && ! empty($filters['site'])) {
            $query->whereHas('employee.hires', function ($q) use ($filters): void {
                $q->whereIn('site_id', $filters['site']);
            });
        }

        if (isset($filters['project']) && ! empty($filters['project'])) {
            $query->whereHas('employee.hires', function ($q) use ($filters): void {
                $q->whereIn('project_id', $filters['project']);
            });
        }

        if (isset($filters['supervisor']) && ! empty($filters['supervisor'])) {
            $query->whereIn('supervisor_id', $filters['supervisor']);
        }

        $total = (clone $query)->count();
        $requested = (clone $query)->where('status', HRActivityRequestStatuses::Requested)->count();
        $inProgress = (clone $query)->where('status', HRActivityRequestStatuses::InProgress)->count();
        $completed = (clone $query)->where('status', HRActivityRequestStatuses::Completed)->count();

        // Try to generate URLs, but don't fail if routes aren't registered (e.g., in tests)
        try {
            $indexUrl = HRActivityRequestResource::getUrl('index');
            $requestedUrl = HRActivityRequestResource::getUrl('index', [
                'filters' => [
                    'status' => ['value' => HRActivityRequestStatuses::Requested->value],
                ],
            ]);
            $inProgressUrl = HRActivityRequestResource::getUrl('index', [
                'filters' => [
                    'status' => ['value' => HRActivityRequestStatuses::InProgress->value],
                ],
            ]);
            $completedUrl = HRActivityRequestResource::getUrl('index', [
                'filters' => [
                    'status' => ['value' => HRActivityRequestStatuses::Completed->value],
                ],
            ]);
        } catch (\Exception $e) {
            $indexUrl = $requestedUrl = $inProgressUrl = $completedUrl = null;
        }

        return [
            Stat::make('Total Requests', $total)
                ->description('All time')
                ->url($indexUrl)
                ->color('primary'),
            Stat::make('Requested', $requested)
                ->description('Pending review')
                ->url($requestedUrl)
                ->color('info'),
            Stat::make('In Progress', $inProgress)
                ->description('Being processed')
                ->url($inProgressUrl)
                ->color('warning'),
            Stat::make('Completed', $completed)
                ->description('Finished')
                ->url($completedUrl)
                ->color('success'),
        ];
    }
}
