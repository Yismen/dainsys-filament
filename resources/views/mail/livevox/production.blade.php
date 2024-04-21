<x-mail::message>
# {{ $title }}

Please see attached the {{ $title }} updated!

{{-- <x-mail::button :url="$url">
View Order
</x-mail::button> --}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
