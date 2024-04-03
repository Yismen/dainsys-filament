<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\OvernightHour;
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

class OvernightHourImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithValidation, WithMapping, WithUpserts, WithEvents
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
        return new OvernightHour($row);
    }
    public function rules(): array
    {
        return [
            '*.date' => ['required', 'date'],
            '*.employee_id' => ['exists:employees,id'],
            '*.hours' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function map($row): array
    {
        return [
            'date' => Carbon::parse($row['date'] ?? ''),
            'employee_id' => $row['employee_id'] ?? '',
            'hours' => $row['hours'] ?? '',

        ];
    }

    public function uniqueBy(): array
    {
        return ['employee_id', 'date'];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
