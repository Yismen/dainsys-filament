<x-mail::message>
# Birdays {{ $type }}

The following employees are having birthday {{ str($type)->lower() }}:

<x-mail::table>
| Name | Site | Project | Date of Birth | Age |
| :----- | :----- | :------------- | :---------------- | :------------ |
@foreach ($birthdays as $birthday)
| {{ $birthday['name'] }} | {{ $birthday['site'] }} | {{ $birthday['project'] }} | {{ $birthday['date_of_birth'] }} | {{
$birthday['age'] }} |
@endforeach
</x-mail::table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
