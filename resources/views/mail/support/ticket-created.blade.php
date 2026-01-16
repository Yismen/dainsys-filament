<x-mail::message>
# Hello,

A new ticket with #{{ $ticket->reference }} has been created by {{ $ticket->owner->name }}!

**Title: {{ $ticket->subject }}**

*Content:*
> {!! $ticket->description !!}

<x-mail::button :url="url('support/tickets', ['record' => $ticket->getKey()])">
    View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
