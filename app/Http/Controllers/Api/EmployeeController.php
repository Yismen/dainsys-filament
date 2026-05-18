<?php

namespace App\Http\Controllers\Api;

use App\Filters\ByPosition;
use App\Filters\ByProject;
use App\Filters\BySite;
use App\Filters\ByStatus;
use App\Filters\BySupervisor;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class EmployeeController extends Controller
{
    #[QueryParameter('status', description: 'Status of the employee to filter. Options are: active (only active employees), inactive (only inactive employees), recents (active employees or terminated in the last 45 days)')]
    #[QueryParameter('site', description: 'ID or Name of the site to filter employees')]
    #[QueryParameter('project', description: 'ID or Name of the project to filter employees')]
    #[QueryParameter('position', description: 'ID or Name of the position to filter employees')]
    #[QueryParameter('supervisor', description: 'ID or Name of the supervisor to filter employees')]
    public function __invoke(Request $request)
    {
        $employees = app(Pipeline::class)
            ->send(
                Employee::query()
                    ->orderBy('full_name')
                    ->with([
                        'project:id,name',
                        'position:id,name,salary_type,salary,department_id' => [
                            'department:id,name',
                        ],
                        'supervisor:id,name',
                        'site:id,name',
                        'universal:employee_id',
                        'bankAccount:id,account,bank_id,employee_id' => [
                            'bank:id,name',
                        ],
                    ])
            )
            ->through([
                ByStatus::class,
                ByProject::class,
                ByPosition::class,
                BySupervisor::class,
                BySite::class,
            ])
            ->thenReturn()
            ->get();

        return EmployeeResource::collection($employees);
    }
}
