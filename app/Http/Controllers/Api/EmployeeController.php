<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;

class EmployeeController extends Controller
{
    #[QueryParameter('status', description: 'Status of the employee to filter')]
    #[QueryParameter('site', description: 'ID or Name of the site to filter employees')]
    #[QueryParameter('project', description: 'ID or Name of the project to filter employees')]
    #[QueryParameter('position', description: 'ID or Name of the position to filter employees')]
    #[QueryParameter('supervisor', description: 'ID or Name of the supervisor to filter employees')]
    public function __invoke(Request $request)
    {
        $query_filters = $request->uri()->query()->all();
        $class_string = \str(self::class)->replace('\\', ' ')->snake()->toString();
        $query_string = $request->getQueryString();
        $cache_key = $class_string.$query_string;
        $employees = Cache::rememberForever($cache_key, function () {
            $employee = app(Pipeline::class)
                ->send(
                    Employee::query()
                        ->orderBy('full_name')
                        ->with([
                            'project:id,name',
                            'position:id,name',
                            'supervisor:id,name',
                            'site:id,name',
                        ])
                )
                ->through([
                    \App\Filters\ByStatus::class,
                    \App\Filters\ByProject::class,
                    \App\Filters\ByPosition::class,
                    \App\Filters\BySupervisor::class,
                    \App\Filters\BySite::class,
                ])
                ->thenReturn()
                ->get();

            return $employee;
        });

        return EmployeeResource::collection($employees);
    }
}
