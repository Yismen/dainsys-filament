@component('mail::message')
# Hello,

Please see attached a report with all expired tickets. Please review and work accordingly.

{{-- <x-support::email.button :url="route('support.dashboard')"> Dashboard </x-support::email.button> --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
