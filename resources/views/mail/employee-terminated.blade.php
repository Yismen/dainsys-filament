@component('mail::message')
@php
    $employee = $termination->employee;
@endphp
# Employee {{ $employee->full_name }} Terminated

Employee <b>{{ $employee->full_name }}</b> has been terminated, efectivily {{ $termination->date->format('M-d, Y') }}! They worked at {{ $employee->site->name }}, im project {{ $employee->project->name }} as {{ $employee->position }}, for supervisor {{ $employee->supervisor->name }}.

@component('mail::button', ['url' => '/human-resource/employees-management/employees'])
{{ $employee->full_name }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
