<x-mail::message>
@php
    $employee = $termination->employee;
@endphp
# Employee {{ $employee->full_name }} Terminated

Employee <b>{{ $employee->full_name }}</b> has been terminated, efectivily {{ $termination->date->format('M-d, Y') }}! They worked at {{ $employee->site->name }}, in project {{ $employee->project->name }} as {{ $employee->position }}, for supervisor {{ $employee->supervisor->name }}.

<x-mail::button :url="url('human-resource/employees')">
    View Employees
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
