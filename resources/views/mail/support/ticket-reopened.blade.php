<x-mail::message>
# Hello,

Ticket #{{ $ticket->reference }}, created by {{ $ticket->owner->name }} {{ $ticket->created_at->diffForHumans() }}, was
reopened by {{ $user?->name }}!.

**Title: {{ $ticket->subject }}**

*Content:*
> {!! $ticket->description !!}

<x-mail::button :url="url('support/tickets', ['record' => $ticket->getKey()])">
    View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
