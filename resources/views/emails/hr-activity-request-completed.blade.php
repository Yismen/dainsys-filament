<x-mail::message>
# HR Activity Request Completed

An HR activity request has been completed.

**Employee:** {{ $employee->full_name }}  
**Supervisor:** {{ $supervisor->name }}  
**Activity Type:** {{ $request->activity_type->value }}  
**Requested At:** {{ $request->requested_at->format('F j, Y g:i A') }}  
**Completed At:** {{ $request->completed_at->format('F j, Y g:i A') }}

@if($request->description)
**Original Request:**  
{{ $request->description }}
@endif

**Completion Comment:**  
{{ $comment }}

<x-mail::button :url="url('/human-resource/h-r-activity-requests/'.$request->id)">
View Request
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
