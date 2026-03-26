<x-mail::message>
# Hello,

{{ $user?->name }} has replied on ticket #{{ $reply->ticket->reference }} with the following message:

> *"{{ $reply->content }}"*

<x-mail::button :url="url('support')">
    View Tickets
</x-mail::button>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
