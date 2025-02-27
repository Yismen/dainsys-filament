@component('mail::message')
# Hello,

{{ $user?->name }} has replied on ticket #{{ $reply->ticket->reference }} with the following message:

> *"{{ $reply->content }}"*

{{-- <x-support::email.button :url="route('support.my_tickets', ['ticket_details' => $reply->ticket->id])">
    View
    Ticket</x-support::email.button> --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
