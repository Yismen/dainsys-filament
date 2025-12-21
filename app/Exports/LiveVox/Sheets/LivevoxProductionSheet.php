<?php

namespace App\Exports\LiveVox\Sheets;

use App\Models\Services\LivevoxAgentSessionService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LivevoxProductionSheet implements FromQuery, WithHeadings, WithTitle
{
    protected array $default_group_columns = [
        'report_date' => 'Date',
        'service_name' => 'Service',
        'agent_name' => 'Agent',
    ];

    public function __construct(
        protected string|array $service_name,
        protected Carbon $date_from,
        protected array $group_columns,
        protected ?Carbon $date_to,
    ) {}

    public function query()
    {
        $service = app(LivevoxAgentSessionService::class, [
            'service_name' => $this->service_name,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'group_columns' => $this->group_columns,
        ]);

        return $service->query();
    }

    public function title(): string
    {
        return 'Production';
    }

    public function headings(): array
    {
        $fields = [];
        foreach (array_intersect(array_keys($this->default_group_columns), array_values($this->group_columns)) as $field) {
            $fields[] = $this->default_group_columns[$field];
        }

        return array_merge(
            $fields,
            [
                'Online Time',
                'Prod Time',
                'Available Time',
                'Talk Time',
                'Wrap Time',
                'Not Ready Time',
                'Calls',
                'Contacts',
            ]
        );
    }
}
