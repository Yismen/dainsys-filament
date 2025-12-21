<?php

namespace App\Models\Services;

use App\Models\LiveVox\LivevoxAgentSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class LivevoxAgentSessionService
{
    public function __construct(
        protected string|array $service_name,
        protected string|Carbon $date_from,
        protected string|Carbon|null $date_to = null,
        protected array $group_columns = [
            'report_date',
            'service_name',
            'agent_name',
        ]
    ) {}

    public function query(): Builder
    {
        $query = LivevoxAgentSession::query();

        if (is_array($this->service_name)) {
            foreach ($this->service_name as $service_name) {
                $query = $query->where('service_name', 'like', $service_name);
            }
        } else {
            $query = $query->where('service_name', 'like', $this->service_name);
        }

        if ($this->date_to) {
            $query = $query->whereDate('report_date', '>=', $this->date_from)
                ->whereDate('report_date', '<=', $this->date_to);
        } else {
            $query = $query->whereDate('report_date', $this->date_from);
        }

        $query = $query
            ->select($this->group_columns)
            ->selectRaw('convert(float, sum(online_time_seconds))  / 3600 as total_online_time')
            ->selectRaw('convert(float, (sum(average_available_time_seconds * successful_operator_transfers) + sum(average_talk_time_seconds * successful_operator_transfers) + sum(average_wrap_time_seconds * successful_operator_transfers))) / 3600 as total_prod_time')
            ->selectRaw('convert(float, sum(average_available_time_seconds * successful_operator_transfers)) / 3600 as total_available_time')
            ->selectRaw('convert(float, sum(average_talk_time_seconds * successful_operator_transfers)) / 3600 as total_talk_time')
            ->selectRaw('convert(float, sum(average_wrap_time_seconds * successful_operator_transfers)) / 3600 as total_wrap_time')
            ->selectRaw('convert(float, sum(average_not_ready_time_seconds * successful_operator_transfers)) / 3600 as total_not_ready')
            ->selectRaw('sum(successful_operator_transfers) as total_calls')
            ->selectRaw('sum(total_rpcs) as total_contacts');

        foreach ($this->group_columns as $column) {
            $query = $query->groupBy($column);
            $query = $query->orderBy($column);
        }

        return $query;
    }
}
