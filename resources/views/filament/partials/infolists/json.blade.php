<table>
    <thead>
        <tr>
            <th class="text-left font-bold border bg-gray-200 p-1">Field</th>
            @if ($json['old'] ?? null)
                <th class="text-left font-bold border bg-gray-200 p-1">Old</th>
            @endif
            <th class="text-left font-bold border bg-gray-200 p-1">New</th>
        </tr>
    </thead>

    @foreach ($json['attributes'] ?? [] as $field => $value)
        <tr>
            <td class="text-left p-1 border">{{ str($field)->headline() }}</td>
            @if ($json['old'] ?? null)
                <td class="text-left p-1 border">{{ $json['old'][$field] ?? '' }}</td>
            @endif
            <td class="text-left p-1 border">{{ $value }}</td>
        </tr>
    @endforeach
</table>

{{-- @foreach ($json as $key => $item)
    <h5>
        <strong>{{ str($key)->headline() }}</strong>
    </h5>
    <ul style="margin-left: 20px;">
        @foreach ($item as $subKey => $subItem)
            <li>
                <strong>{{ str($subKey)->headline() }}:</strong> {{ is_array($subItem) || is_object($subItem) ? json_encode($subItem) : $subItem }}
            </li>
        @endforeach
    </ul>


    <hr>
@endforeach

{{ $json }} --}}
