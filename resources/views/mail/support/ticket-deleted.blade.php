<x-mail::message>
# Hello,

Ticket #{{ $ticket->reference }}, created by {{ $ticket->owner->name }} on {{ $ticket->created_at->diffForHumans() }}, has
been deleted by {{ $user?->name }}.

**Title: {{ $ticket->subject }}**

*Content:*
> {!! $ticket->description !!}

<x-mail::button :url="url('support')">
    View Tickets
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
