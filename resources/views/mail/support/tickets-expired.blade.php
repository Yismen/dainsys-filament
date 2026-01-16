<x-mail::message>
# Tickets Expired Report

Please find attached a report that includes all tickets which are currently expired and have not yet been completed. Kindly ensure that these tickets are assigned to one of your team members for prompt attention.

<x-mail::button :url="url('support')">
View All Tickets
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
