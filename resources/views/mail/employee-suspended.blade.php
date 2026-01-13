<x-mail::message>
@php
    $employee = $suspension->employee;
@endphp
# Employee Suspended

Employee <b>{{ $employee->full_name }}</b> will be out due to a {{ $suspension->suspensionType->name }}, from {{ $suspension->starts_at->format('M-d, Y') }} to {{ $suspension->ends_at->format('M-d, Y') }}. During this period
the employee is considered suspended, therefore they should not be working. Also, any NCNS during this period should not be considered as an absence. Please plan accordingly.

This employee works for project {{ $employee->project->name }} in site {{ $employee->site->name }} as a {{ $employee->position->name }}, and reports to {{ $employee->supervisor->name }}.

<x-mail::button :url="url('human-resource/employees-management/employees', ['record' => $employee->getKey()])">
    View Employee
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
