<x-mail::message>
# Hello,

A new ticket with #{{ $ticket->reference }} has been created by {{ $ticket->owner->name }}!

**Title: {{ $ticket->subject }}**

*Content:*
> {!! $ticket->description !!}

<x-mail::button :url="url('support')">
    View Tickets
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
