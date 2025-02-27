@component('mail::message')
# Hello,

Ticket #{{ $ticket->reference }}, created by {{ $ticket->owner->name }} {{ $ticket->created_at->diffForHumans() }}, was
reopened by {{ $user?->name }}!.

**Title: {{ $ticket->subject }}**

*Content:*
> {!! $ticket->description !!}

{{-- <x-support::email.button :url="route('support.my_tickets', ['ticket_details' => $ticket->id])">View
    Ticket</x-support::email.button> --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
