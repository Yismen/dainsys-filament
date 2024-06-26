@component('mail::message')
# Employee Created

Employee <b>{{ $employee->full_name }}</b>, who was previously part of the company, was reactivated with date {{
$employee->hired_at->format('Y-m-d') }}.

{{-- @component('mail::button', ['url' => ''])
{{ str(__('dainsys::messages.profile'))->headline() }}
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent