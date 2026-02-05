<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeApiRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;

class EmployeeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query_filters = $request->uri()->query()->all();
        $class_string = \str(self::class)->replace('\\', ' ')->snake()->toString();
        $query_string = $request->getQueryString();
        $cache_key = $class_string.$query_string;

                //  'citizenship_id',
                // 'site_id',
                // 'project_id',
                // 'position_id',
                // 'supervisor_id',
                // 'hired_at',
                // 'internal_id',
                // 'status',

        $employees = Cache::rememberForever($cache_key, function () {
            $employee = app(Pipeline::class)
                ->send(
                    Employee::query()
                        ->orderBy('full_name')
                        ->with([
                            'site:id,name',
                        ])
                )
                ->through([
                    \App\Filters\ByStatus::class,
                    // \App\Filters\ByProject::class,
                    // \App\Filters\ByEmployee::class,
                    // \App\Filters\BySupervisor::class,
                    \App\Filters\ByEmployeeSite::class,
                ])
                ->thenReturn()
                ->get();

            return $employee;
        });

        return EmployeeResource::collection($employees);
    }
}
