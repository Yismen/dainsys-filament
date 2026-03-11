<div class="group flex flex-col h-80 bg-white dark:bg-gray-800 rounded-xl border border-gray-200/70 dark:border-gray-700/70 p-4 shadow-sm transition-transform duration-200 hover:-translate-y-1 hover:shadow-lg" wire:key="supervisor-{{ $supervisor->id }}">
    <div class="flex flex-col items-start justify-between gap-3 pb-3 overflow-y-hidden">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white w-full flex items-center justify-between">
            {{ $supervisor->name }}
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $supervisor->employees->count() }} employees)</span>
            {{ $toggleAction }}
        </h3>

        <div class="w-full flex items-center justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">
                {{ $supervisor->user ? 'Linked to: ' . $supervisor->user->name : 'No user linked' }}
            </span>
            {{ ($editUserAction)(['supervisor' => $supervisor->id, 'user_id' => $supervisor->user_id]) }}
        </div>

        <livewire:selectable-employees :employees="$supervisor->employees" wire:key="selectable-employees-{{ $supervisor->id }}" supervisor="{{ $supervisor->id }}"/>
    </div>
</div>
