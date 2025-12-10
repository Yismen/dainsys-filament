@component('mail::message')
# Employee Terminated

Employee <b>{{ $termination->employee->full_name }}</b> was terminated with termination by {{
$termination->termination_type }}, with termination date {{ $termination->date->format('Y-m-d') }}. .

{{-- @component('mail::button', ['url' => ''])
{{ str(__('dainsys::messages.profile'))->headline() }}
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
