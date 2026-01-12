<x-mail::message>
# Hello,

A new ticket with #{{ $ticket->reference }} has been created by {{ $ticket->owner->name }}!

**Title: {{ $ticket->subject }}**

*Content:*
> {!! $ticket->description !!}

{{-- <x-support::email.button :url="route('support.my_tickets', ['ticket_details' => $ticket->id])">View Ticket
</x-support::email.button>
Thanks,<br> --}}
{{ config('app.name') }}
</x-mail::message>
