<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Position;
use App\Services\HC\ByDepartment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

// test('example', function () {
//     Mail::fake();
//     Cache::clear();
//     $department = Department::factory()
//         ->has(
//             Position::factory()
//                 ->has(
//                     Hire::factory()
//                         ->has(Employee::factory())
//                 )
//         )
//         ->create();

//     // Hire::factory()->for($employee)->create();

//     $service = new ByDepartment();

//     expect($service->count()->toArray()[0])->toHaveKey('name');
//     expect($service->count()->toArray()[0])->toHaveKey('employees_count');
// });
