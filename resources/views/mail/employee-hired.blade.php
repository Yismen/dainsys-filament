@component('mail::message')
@php
    $employee = $hire->employee;
@endphp
# Welcome {{ $employee->full_name }}

Employee <b>{{ $employee->full_name }}</b> has been hired with hire date {{ $hire->date->format('M-d, Y') }}. This
person was assigned to site {{ $employee->site->name }} and was hired for project {{ $employee->project->name }} as {{ $employee->position->name }}, reporting to {{ $employee->supervisor->name }}. Please give them a warm welcome.

@component('mail::button', ['url' => '/human-resource/employees-management/employees'])
{{ $employee->full_name }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
