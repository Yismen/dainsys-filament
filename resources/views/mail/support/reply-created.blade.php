<x-mail::message>
# Hello,

{{ $user?->name }} has replied on ticket #{{ $reply->ticket->reference }} with the following message:

> *"{{ $reply->content }}"*

{{-- <x-support::email.button :url="route('support.my_tickets', ['ticket_details' => $reply->ticket->id])">
    View
    Ticket</x-support::email.button> --}}
<x-mail::button :url="url('support')">
    View Tickets
</x-mail::button>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
