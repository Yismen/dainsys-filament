<x-mail::message>
@php
    $employee = $hire->employee;
@endphp
# Welcome {{ $employee->full_name }}

Employee <b>{{ $employee->full_name }}</b> has been hired with hire date {{ $hire->date->format('M-d, Y') }}. This
person was assigned to site {{ $hire->site->name }} and was hired for project {{ $hire->project->name }} as {{ $hire->position->name }}, reporting to {{ $hire->supervisor->name }}. Please give them a warm welcome.

<x-mail::button :url="url('human-resource/employees-management/employees', ['record' => $employee->getKey()])">
    View Employee
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
