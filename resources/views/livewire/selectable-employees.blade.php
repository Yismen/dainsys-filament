<div class="flex flex-col w-full">
        @if (count($this->employees))
            <div>
                @if ($this->allSelected === false)
                    <button
                        wire:click="toggleSelectAll"
                        class="shrink-0 text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap"
                    >
                        Select All
                    </button>
                @else
                    <button
                        wire:click="toggleSelectAll"
                        class="shrink-0 text-xs font-medium text-green-600 dark:text-green-400 hover:underline whitespace-nowrap"
                    >
                        Unselect All
                    </button>
                @endif
            </div>

        <div class="flex-1 min-h-0 overflow-y-auto pr-1 flex flex-col gap-2">
            @foreach ($this->employees as $employee)
                <label for="{{ $employee->id }}" class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 transition" wire:key="employee-{{ $employee->id }}">
                    <input
                        value="{{ $employee->id }}"
                        wire:model.live="selectedEmployees"
                        type="checkbox"
                        id="{{ $employee->id }}"
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 dark:border-gray-600 transition hover:scale-110"
                    />
                    <span class="text-wrap cursor-pointer">{{ $employee->full_name }}</span>
                </label>
            @endforeach
        </div>
    @endif
</div>
