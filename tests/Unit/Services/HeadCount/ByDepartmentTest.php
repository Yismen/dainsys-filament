<?php

use App\Services\HC\ByDepartment;
use Illuminate\Support\Facades\Mail;
use Database\Factories\EmployeeFactory;

test('example', function () {
    Mail::fake();
    EmployeeFactory::new()->create();

    $service = new ByDepartment();

    expect($service->count()->toArray()[0])->toHaveKey('name');
    expect($service->count()->toArray()[0])->toHaveKey('employees_count');
});
