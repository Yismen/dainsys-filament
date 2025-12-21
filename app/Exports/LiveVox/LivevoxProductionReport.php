<?php

namespace App\Exports\LiveVox;

use App\Exports\LiveVox\Sheets\LivevoxProductionSheet;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LivevoxProductionReport implements WithMultipleSheets
{
    public function __construct(
        protected string|array $service_name,
        protected Carbon $date_from,
        protected ?Carbon $date_to = null,
        protected array $columns = [
            'report_date',
            'service_name',
            'agent_name',
        ]
    ) {}

    public function sheets(): array
    {
        return [
            new LivevoxProductionSheet(
                service_name: $this->service_name,
                date_from: $this->date_from,
                date_to: $this->date_to,
                group_columns: $this->columns,
            ),
        ];
    }
}
