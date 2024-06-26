@component('mail::message')
# Birdays {{ $type }}

The following employees are having birthday {{ str($type)->lower() }}:

@component('mail::table')
| Name | Site | Project | Date of Birth | Age |
| :----- | :----- | :------------- | :---------------- | :------------ |
@foreach ($birthdays as $birthday)
| {{ $birthday['name'] }} | {{ $birthday['site'] }} | {{ $birthday['project'] }} | {{ $birthday['date_of_birth'] }} | {{
$birthday['age'] }} |
@endforeach
@endcomponent

{{-- @component('mail::button', ['url' => ''])
{{ str(__('dainsys::messages.profile'))->headline() }}
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
