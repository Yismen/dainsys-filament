<?php

namespace App\Console\Commands;

use App\Models\Downtime;
use App\Models\Production;
use App\Models\PayrollHour;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ImportPayrollHoursFromProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:import-payroll-hours-from-production {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';



    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date');
        $start_of_week = Carbon::parse($date)->startOfWeek();
        $end_of_week = Carbon::parse($date)->endOfWeek();

        $productions = Production::query()
            ->whereDate('date', '>=', $start_of_week)
            ->whereDate('date', '<=', $end_of_week)
            ->orderBy('date', 'asc')
            ->groupBy(['date', 'employee_id'])
            ->select([
                'date',
                'employee_id',
                DB::raw("sum(total_time) as sum_of_total_time")
            ])
            ->get();

        $productions->each(function(Production $production) {
            PayrollHour::updateOrCreate(
                [
                    'employee_id' => $production->employee_id,
                    'date' => Carbon::parse($production->date),
                ],
                [
                    'total_hours' => $production->sum_of_total_time,
                ]
            );
        });
    }

    protected function getModelData(
        Builder $modelBuilder,
        Carbon $start_of_week,
        Carbon $end_of_week,
        array $groupFields = ['employee_id', 'date'],
        string $hoursField = 'total_time'
    )
    {
        return $modelBuilder
            ->orderBy('date', 'asc')
            ->whereDate('date', '>=', $start_of_week)
            ->whereDate('date', '<=', $end_of_week)
            ->groupBy(['date', 'employee_id'])
            ->select([
                'date',
                'employee_id',
                DB::raw("sum({$hoursField}) as sum_of_{$hoursField}")
            ]
            )
            ->get();
    }
}
