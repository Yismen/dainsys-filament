<x-mail::message>
# New HR Activity Request

A new HR activity request has been created.

**Employee:** {{ $employee->full_name }}  
**Supervisor:** {{ $supervisor->name }}  
**Activity Type:** {{ $request->activity_type->value }}  
**Status:** {{ $request->status->value }}  
**Requested At:** {{ $request->requested_at->format('F j, Y g:i A') }}

@if($request->description)
**Description:**  
{{ $request->description }}
@endif

<x-mail::button :url="url('/human-resource/h-r-activity-requests/'.$request->id)">
View Request
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
