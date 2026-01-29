@if ($this->employeesWithoutSupervisor->count())
    {{-- Supervisors with Employees --}}
    <h3 class="text-blue-600 dark:text-blue-400 mb-2">Employees Without Supervisors Assigned</h3>
    <div class="grid gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3 items-stretch ">
        @foreach ($this->employeesWithoutSupervisor as $split)

                <div class="flex-1 min-h-0 overflow-y-auto pr-1 flex flex-col gap-2">
                    @foreach ($split as $employee)
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
        @endforeach
    </div>
@endif

