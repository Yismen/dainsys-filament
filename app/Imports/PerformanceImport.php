<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Performance;
use App\Jobs\UpdateRevenueAndBillable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Events\AfterBatch;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class PerformanceImport implements ToModel, WithHeadingRow, WithMapping, WithChunkReading, WithBatchInserts, WithValidation, WithUpserts, WithEvents
{
    use RegistersEventListeners;

    protected string $filename;
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Performance($row);
    }

    public function map($row): array
    {
        return [
            'file' => $this->filename,
            'date' => Carbon::parse($row['date']),
            'employee_id' => $row['employee_id'],
            'campaign_id' => $row['campaign_id'],
            'campaign_goal' => $row['sph_goal'],
            'login_time' => $row['login_time_parsed'],
            'production_time' => $row['production_time_parsed'],
            'talk_time' => $row['talk_time_parsed'],
            'billable_time' => $row['billable_hours'],
            'attempts' => $row['calls'],
            'contacts' => $row['contacts'],
            'successes' => $row['transactions'],
            'upsales' => $row['upsales'],
            'revenue' => $row['revenue'],
            'downtime_reason_id' => $row['reason_id'],
            'reporter_id' => $row['reported_by'],

        ];
    }

    public function uniqueBy(): array
    {
        return ['employee_id', 'campaign_id', 'date'];
    }
    public function rules(): array
    {
        return [
            '*.file' => ['required'],
            '*.date' => ['required', 'date'],
            '*.employee_id' => ['exists:employees,id'],
            '*.campaign_id' => ['exists:campaigns,id'],
            '*.campaign_goal' => ['required', 'numeric'],
            '*.login_time' => ['required', 'numeric'],
            '*.production_time' => ['required', 'numeric'],
            '*.talk_time' => ['required', 'numeric'],
            '*.billable_time' => ['required', 'numeric'],
            '*.attempts' => ['required', 'numeric'],
            '*.contacts' => ['required', 'numeric'],
            '*.successes' => ['required', 'numeric'],
            '*.upsales' => ['required', 'numeric'],
            '*.revenue' => ['required', 'numeric'],
            '*.downtime_reason_id' => ['nullable', 'exists:downtime_reasons,id'],
            '*.reporter_id' => ['nullable', 'exists:supervisors,id'],
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public static function afterImport(AfterImport $event)
    {
        UpdateRevenueAndBillable::dispatch(now());
    }
}
