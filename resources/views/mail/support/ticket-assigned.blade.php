<x-mail::message>
# Hello,

Ticket #{{ $ticket->reference }}, created by {{ $ticket->owner->name }} {{ $ticket->created_at->diffForHumans() }}, has
been assigned to **{{ $ticket->agent?->name }}** by {{ $user?->name }}.

This ticket {{ $ticket->expected_at->isPast() ? 'was' : 'is' }} expected {{ $ticket->expected_at->diffForHumans() }}

**Title: {{ $ticket->subject }}**

*Content:*
> {!! $ticket->description !!}

<x-mail::button :url="url('support/tickets', ['record' => $ticket->getKey()])">
    View Ticket
</x-mail::button>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
