<x-mail::message>
# Employee Created

Employee <b>{{ $employee->full_name }}</b>, who was previously part of the company, was reactivated with date {{
$employee->hired_at->format('Y-m-d') }}.

<x-mail::button :url="url('human-resource/employees-management/employees', ['record' => $employee->getKey()])">
    View Employee
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
