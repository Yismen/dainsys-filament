@if ($this->inactiveSupervisors->count())
    {{-- Inactive Supervisors with Employees --}}
    <h3 class="text-red-600 dark:text-red-400 mb-2">Inactive Supervisors</h3>
    <div class="grid gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3 items-stretch ">
        @foreach ($this->inactiveSupervisors as $supervisor)
            <div class="group flex flex-col h-80 bg-white dark:bg-gray-800 rounded-xl border border-gray-200/70 dark:border-gray-700/70 p-4 shadow-sm transition-transform duration-200 hover:-translate-y-1 hover:shadow-lg" wire:key="supervisor-{{ $supervisor->id }}">
                <div class="flex flex-col items-start justify-between gap-3 pb-3">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white truncate max-w-[70%]">
                        {{ $supervisor->name }}
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $supervisor->employees->count() }} employees)</span>
                    </h3>

                    <div class="flex flex-row justify-between w-full">
                        @if ($supervisor->employees->count())
                            <button
                                wire:click="selectAllForSupervisor('{{ $supervisor->id }}')"
                                class="shrink-0 text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap"
                            >
                                Select All
                            </button>
                        @endif
                        {{ ($this->reactivateSupervisorAction)(['supervisor' => $supervisor->id]) }}
                    </div>
                </div>
                <div class="flex-1 min-h-0 overflow-y-auto pr-1 flex flex-col gap-2">
                    @foreach ($supervisor->employees as $employee)
                        <label for="{{ $employee->id }}" class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 transition" wire:key="employee-{{ $employee->id }}">
                            <input
                                value="{{ $employee->id }}"
                                wire:model.live="selectedEmployees"
                                type="checkbox"
                                id="{{ $employee->id }}"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 dark:border-gray-600 transition hover:scale-110"
                            />
                            <span class="truncate">{{ $employee->full_name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif
