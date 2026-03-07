@props([
    'label',
    'state',
    'labelColor' => 'text-gray-900 dark:text-white',
    'stateColor' => 'text-gray-600 dark:text-gray-400',
])
<div class="flex flex-col">
    <div class="text-sm font-semibold {{ $labelColor }}">
        {{ $label }}
    </div>
    <div class="text-sm font-normal {{ $stateColor }}">
        {{ $state }}
    </div>
</div>
